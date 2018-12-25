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
     * @param \App\Models\FTPFile $ftpFile
     *
     * @return \App\Models\Media
     * @throws \App\Exceptions\FtpFileNotFoundException
     * @throws \ImagickException
     * @throws \Exception
     */
    public function handleFTPFile(FTPFile $ftpFile): Media
    {
        if (!file_exists($ftpFile->file)) {
            throw new FtpFileNotFoundException('File not found', $ftpFile);
        }

        /**
         * @var Media[] $medias
         */
        $ftpFile->processing = true;
        $ftpFile->save();

        $file = new File($ftpFile->file);
        $brand = $ftpFile->ftpUser->brand;

        if (UploadService::calculateUsedStorageFull($brand) + $file->getSize() >= $brand->getProduct()->storage) {
            throw new \LogicException('This file bigger than you have the free space');
        }


        try {

            $media = new Media([
                'file_name' => Media::FILE_PREFIX . $file->hashName(),
                'origin_name' => $file->getFilename(),
                'content_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'brand_id' => $brand->id,
                'dir' => $brand->getImagePath()
            ]);


            if (\in_array($file->extension(), ['ai', 'eps'])) {

                $status = Storage::disk('s3')->putFileAs(
                    $media->brand->getImagePath(),
                    $file,
                    Media::FILE_PREFIX . $file->hashName()
                );

                $image = new \Imagick();
                $image->readImage($file->getRealPath());
                $dimensions = $image->getImageGeometry();
                $width = $dimensions['width'];
                $height = $dimensions['height'];
                $maxWidth = 600;
                $maxHeight = 600;


                if ($height >= $width) {
                    //Portrait
                    if ($height > $maxHeight) {
                        $image->thumbnailImage(0, $maxHeight, false);
                    } else {
                        $image->thumbnailImage(0, $height, false);
                    }
                } elseif ($height < $width) {
                    if ($width > $maxWidth) {
                        $image->thumbnailImage($maxWidth, 0, false);
                    } else {
                        $image->thumbnailImage($width, 0, false);
                    }
                }

                $image->writeImage(storage_path('app/brands_thumbnail/' . $media->brand->id . '/' . $media->file_name . '.jpeg'));
                $media->thumbnail = $media->file_name . '.jpeg';
                $media->width = $image->getImageWidth();
                $media->height = $image->getImageHeight();

            } elseif (\in_array($file->extension(), ['mp4'])) {

                $media = UploadService::setVideoData($media, $file, $brand);
                $status = Storage::disk('s3')->putFileAs(
                    $media->brand->getImagePath(),
                    $file,
                    Media::FILE_PREFIX . $file->hashName()
                );

            } else {
                $this->mediaManager->read($file->getRealPath());

                $status = Storage::disk('s3')->putFileAs(
                    $media->brand->getImagePath(),
                    $file,
                    Media::FILE_PREFIX . $file->hashName()
                );

                $media = $this->mediaManager->setSizes($media);
                $status = $status && $this->mediaManager->makeAndStoreThumbnail($media);
                $media->thumbnail = $media->file_name;

                $iptc = new ImageMetadataParser($file);
                if ($iptc->parseIPTC()) {
                    $media->setIPTC($iptc->parseIPTC());
                }
                $media->setEXIF(Reader::factory(Reader::TYPE_NATIVE)->read($file));
            }

            if (!$status) {
                throw new \LogicException('Can\'t upload file');
            }

            try {

                $media->title = $media->getIPTC() ? $media->getIPTC()->getTitle() : '';

            } catch (\Exception $exception) {
            }

            $media->keywords = $media->getEXIF() && $media->getEXIF()->getKeywords() ? implode(', ',
                $media->getEXIF()->getKeywords()) : '';
            $media->source = $media->getEXIF() && $media->getEXIF()->getSource() ? $media->getEXIF()->getSource() : '';
            $media->save();

            $ftpFile->media()->associate($media);
            $ftpFile->handled = true;
            $ftpFile->handled_at = Carbon::now();
            $ftpFile->processing = false;
            $ftpFile->save();

            event(new UploadedFileEvent($media, $brand));
            return $media;


        } catch (\Exception $exception) {
            $ftpFile->delete();
            if($media) {
                UploadService::removeMedia($media);
            }
            throw new \LogicException($exception);
        }
    }
}
