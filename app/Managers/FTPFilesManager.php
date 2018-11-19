<?php

// namespace
namespace App\Managers;

// use
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
        /**
         * @var Media[] $medias
         */
        $ftpFile->processing = true;
        $ftpFile->save();

        $file = new File($ftpFile->file);
        $brand = $ftpFile->ftpUser->brand;

        $media = (new Media())
            ->where('file_name', $file->getFilename())
            ->where('dir', $brand->id)
            ->first();

        /**
         * @var FileProcessorInterface $processor
         */
        $processorClass = $this->processors[$file->getMimeType()];
        $processor = \App::get($processorClass);

        $media = $processor->getMediaForFile($file->getRealPath(), $brand, $media);

        $media->save();
        $ftpFile->media()->associate($media);
        $ftpFile->handled = true;
        $ftpFile->handled_at = Carbon::now();
        $ftpFile->processing = false;
        $ftpFile->save();

        return $media;
    }
}
