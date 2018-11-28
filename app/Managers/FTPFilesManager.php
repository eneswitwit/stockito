<?php

namespace App\Managers;

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
     * @param MediaManager $mediaManager
     * @param UploadService $uploadService
     */
    public function __construct(MediaManager $mediaManager, UploadService $uploadService)
    {
        $this->mediaManager = $mediaManager;
        $this->uploadService = $uploadService;
    }

    /**
     * @param FTPFile $ftpFile
     * @return Media
     * @throws FtpFileNotFoundException
     */
    public function handleFTPFile(FTPFile $ftpFile): Media
    {
        if (!file_exists($ftpFile->file)) {
            throw new FtpFileNotFoundException('File not found', $ftpFile);
        }

        $ftpFile->processing = true;
        $ftpFile->save();
        $file = new File($ftpFile->file);
        $brand = $ftpFile->ftpUser->brand;
        $media = new Media([
            'file_name' => Media::FILE_PREFIX . $file->hashName(),
            'origin_name' => $file->getFilename(),
            'content_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'brand_id' => $brand->id,
            'dir' => $brand->getImagePath()
        ]);

        if (\in_array($file->extension(), ['ai', 'eps'])) {
            $status = Storage::disk('s3')->putFileAs($media->brand->getImagePath(), $file, Media::FILE_PREFIX . $file->hashName());
            $image = new \Imagick();
            $image->readImage($file->getRealPath());
            $image->thumbnailImage(640, 480);
            $image->writeImage(storage_path('app/brands_thumbnail/' . $media->brand->id . '/' . $media->file_name . '.png'));
            $media->thumbnail = $media->file_name . '.png';
            $media->width = $image->getImageWidth();
            $media->height = $image->getImageHeight();
        } elseif (\in_array($file->extension(), ['mp4'])) {
            $media = $this->setVideoData($media, $file, $brand);
            $status = Storage::disk('s3')->putFileAs($media->brand->getImagePath(), $file, Media::FILE_PREFIX . $file->hashName());
        } else {
            $this->mediaManager->read($file->getRealPath());
            $status = Storage::disk('s3')->putFileAs($media->brand->getImagePath(), $file, Media::FILE_PREFIX . $file->hashName());
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

        return $media;
    }
}
