<?php

// namespace
namespace App\Managers;

// use
use App\Events\UploadedFileEvent;
use App\Exceptions\FtpFileNotFoundException;
use App\Managers\Processors\AIFileProcessor;
use App\Managers\Processors\EPSFileProcessor;
use App\Managers\Processors\FileProcessorInterface;
use App\Managers\Processors\ImageFileProcessor;
use App\Managers\Processors\Mp4FileProcessor;
use App\Models\FTPFile;
use App\Models\Media;
use App\Services\UploadService;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\Classes\ImageMetadataParser;
use Illuminate\Support\Str;
use PHPExif\Reader\Reader;
use Log;

/**
 * Class FTPFilesManager
 *
 * @package App\Managers
 */
class FTPFilesManager
{
    /**
     * @var MediaManager
     */
    protected $mediaManager;

    /**
     * @var UploadService
     */
    protected $uploadService;

    /**
     * @var FileProcessorInterface[]
     */
    protected $processors = [
        Media::JPEG_MIME => ImageFileProcessor::class,
        Media::MP4_MIME => Mp4FileProcessor::class,
        Media::EPS_MIME => ImageFileProcessor::class,
        Media::AI_MIME => AIFileProcessor::class,
        Media::EPS_MIME => EPSFileProcessor::class
    ];

    /**
     * FTPFilesManager constructor.
     *
     * @param MediaManager $mediaManager
     * @param UploadService $uploadService
     */
    public function __construct(MediaManager $mediaManager, UploadService $uploadService)
    {
        $this->mediaManager = $mediaManager;
        $this->uploadService = $uploadService;
    }

    /**
     * Get a filename for the file.
     *
     * @param $file
     * @param  string $path
     *
     * @return string
     */
    public static function hashName($file, $path = null)
    {

        if ($path) {
            $path = rtrim($path, '/') . '/';
        }

        $hash = $file->hashName() ? substr($file->hashName(), 0, strpos($file->hashName(), ".")) : Str::random(40);

        return $path . $hash . '.' . $file->getExtension();
    }

    /**
     * @param \App\Models\FTPFile $ftpFile
     *
     * @return \App\Models\Media
     * @throws \App\Exceptions\FtpFileNotFoundException
     * @throws \ImagickException
     * @throws \Exception
     */
    public function handleFTPFile(FTPFile $ftpFile)
    {

        if (!file_exists($ftpFile->file)) {
            $ftpFile->delete();
            //throw new \LogicException('The file was not found');
            return null;
        }

        /**
         * @var Media[] $medias
         */
        $ftpFile->processing = true;
        $ftpFile->save();

        $file = new File($ftpFile->file);
        $brand = $ftpFile->ftpUser->brand;
        $user = $ftpFile->ftpUser->user;

        if (UploadService::calculateUsedStorageFull($brand) + $file->getSize() >= $brand->getProduct()->storage) {
            $ftpFile->delete();
            //throw new \LogicException('This file bigger than you have the free space');
            return null;
        }

        try {

            $media = new Media([
                'file_name' => Media::FILE_PREFIX . $this->hashName($file),
                'origin_name' => $file->getFilename(),
                'content_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'brand_id' => $brand->id,
                'dir' => $brand->getImagePath(),
                'created_by' => $user->id
            ]);


            if (\in_array(strtolower($file->getExtension()), ['ai', 'eps'])) {

                $status = \Storage::disk('s3')->putFileAs(
                    $media->brand->getImagePath(),
                    $file,
                    Media::FILE_PREFIX . $this->hashName($file)
                );

                $image = new \Imagick();
                $image->setResolution(300, 300);
                $image->readImage($file->getRealPath());
                $jpeg = storage_path('app/brands_thumbnail/' . $media->brand->id . '/' . $media->file_name . '.jpeg');

                // create good thumbnail
                $dimensions = $image->getImageGeometry();
                $width = $dimensions['width'];
                $height = $dimensions['height'];
                $maxWidth = 600;
                $maxHeight = 600;

                if ($height >= $width) {
                    //Portrait
                    if ($height > $maxHeight) {
                        $cmd = escapeshellcmd("convert -resize 'x{$maxHeight}' -density 600 -flatten {$file->getRealPath()} -colorspace rgb {$jpeg}");
                    } else {
                        $cmd = escapeshellcmd("convert -resize 'x{$height}' -density 600 -flatten {$file->getRealPath()} -colorspace rgb {$jpeg}");
                    }
                } elseif ($height < $width) {
                    if ($width > $maxWidth) {
                        $cmd = escapeshellcmd("convert -resize '{$maxWidth}' -density 600 -flatten {$file->getRealPath()} -colorspace rgb {$jpeg}");
                    } else {
                        $cmd = escapeshellcmd("convert -resize '{$width}' -density 600 -flatten {$file->getRealPath()} -colorspace rgb {$jpeg}");
                    }
                }

                exec($cmd, $out, $return_var);

                $media->thumbnail = $media->file_name . '.jpeg';
                $media->width = $image->getImageWidth();
                $media->height = $image->getImageHeight();


            } elseif (\in_array(strtolower($file->getExtension()), ['mp4', 'mov'])) {

                $media = $this->uploadService->setVideoData($media, $file, $brand);
                $status = \Storage::disk('s3')->putFileAs(
                    $media->brand->getImagePath(),
                    $file,
                    Media::FILE_PREFIX . $this->hashName($file)
                );

            } elseif (\in_array(strtolower($file->getExtension()), ['jpeg', 'jpg'])) {

                $this->mediaManager->read($file->getRealPath());

                $status = \Storage::disk('s3')->putFileAs(
                    $media->brand->getImagePath(),
                    $file,
                    Media::FILE_PREFIX . $this->hashName($file)
                );

                $media = $this->mediaManager->setSizes($media);
                $status = $status && $this->mediaManager->makeAndStoreThumbnail($media);
                $media->thumbnail = $media->file_name;

                $iptc = new ImageMetadataParser($file);

                if ($iptc->parseIPTC()) {
                    $media->setIPTC($iptc->parseIPTC());
                }

                $media->setEXIF(Reader::factory(Reader::TYPE_NATIVE)->read($file));

            } else {

                $ftpFile->delete();
                if (file_exists(storage_path('app/brands/' . $media->getFilePathOrigin()))) {
                    unlink(storage_path('app/brands/' . $media->getFilePathOrigin()));
                }
                if ($media) {
                    UploadService::removeMedia($media);
                }

                //throw new \LogicException('Can\'t upload file. File type not allowed.');
                return null;

            }

            if (!$status) {

                $ftpFile->delete();
                if (file_exists(storage_path('app/brands/' . $media->getFilePathOrigin()))) {
                    unlink(storage_path('app/brands/' . $media->getFilePathOrigin()));
                }
                if ($media) {
                    UploadService::removeMedia($media);
                }

                throw new \LogicException('Can\'t upload file');
            }


            try {
                $media->title = $media->getIPTC() ? $media->getIPTC()->getTitle() : '';
            } catch (\Exception $exception) {
            }

            if($media->getEXIF()) {


                if($media->getEXIF()->getKeywords()) {
                    $media->keywords = $media->getEXIF() && $media->getEXIF()->getKeywords() ? implode(', ',
                        $media->getEXIF()->getKeywords()) : '';
                }

                if($media->getEXIF()->getSource()) {
                    $media->source = $media->getEXIF() && $media->getEXIF()->getSource() ? $media->getEXIF()->getSource() : '';
                }

            }

            $media->save();

            $ftpFile->media()->associate($media);
            $ftpFile->handled = true;
            $ftpFile->handled_at = Carbon::now();
            $ftpFile->processing = false;
            $ftpFile->save();


            if (file_exists(storage_path('app/brands/' . $media->getFilePathOrigin()))) {
                unlink(storage_path('app/brands/' . $media->getFilePathOrigin()));
            }

            event(new UploadedFileEvent($media, $brand));

            return $media;


        } catch (\Exception $exception) {
            $ftpFile->delete();
            if (file_exists(storage_path('app/brands/' . $media->getFilePathOrigin()))) {
                unlink(storage_path('app/brands/' . $media->getFilePathOrigin()));
            }
            if ($media) {
                UploadService::removeMedia($media);
            }
            //throw new \LogicException($exception);
            return null;
        }
    }
}
