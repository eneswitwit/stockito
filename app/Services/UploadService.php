<?php

namespace App\Services;

use App\Managers\MediaManager;
use App\Managers\Processors\Mp4FileProcessor;
use App\Models\Brand;
use App\Models\Media;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;

class UploadService
{
    /**
     * @var MediaManager
     */
    protected $mediaManager;

    /**
     * @var ImageManager
     */
    protected $imageManager;

    /**
     * UploadService constructor.
     * @param MediaManager $mediaManager
     */
    public function __construct(MediaManager $mediaManager, ImageManager $imageManager)
    {
        $this->mediaManager = $mediaManager;
        $this->imageManager = $imageManager;
    }

    /**
     * @param Brand $brand
     * @return array
     */
    public static function calculateUsedStorage(Brand $brand): array
    {
        $sData = [];

        $dir = storage_path('app/brands/'.$brand->getImagePath());

//        $sData['photo'] = self::folderSize($dir, ['jpg', 'pjpg', 'jpeg']);
//        $sData['illustration'] = self::folderSize($dir, ['ai', 'eps']);
//        $sData['video'] = self::folderSize($dir, ['mp4']);
        $sData['all'] = $brand->getProduct() ? $brand->getProduct()->storage : 0;
        $sData['used'] = self::folderSize($dir);

        return $sData;
    }

    /**
     * @param Brand $brand
     * @return int
     */
    public static function calculateUsedStorageFull(Brand $brand): int
    {
        $dir = storage_path('app/brands/'.$brand->getImagePath());
        return self::folderSize($dir);
    }

    /**
     * @param Brand $brand
     * @return array
     */
    public static function formatedUsedStorage(Brand $brand): array
    {
        $sData = self::calculateUsedStorage($brand);

        foreach ($sData as $key => $data) {
            $sData[$key.'Formated'] = self::getUnitsOfBytes($sData[$key]);
        }

        return $sData;
    }

    /**
     * @param string $dir
     * @param array $extensions
     * @return int
     */
    protected static function folderSize(string $dir, array $extensions = []): int
    {
        $size = 0;
        foreach (glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each) {
            if ($extensions) {
                if (is_file($each) && \in_array((new \SplFileInfo($each))->getExtension(), $extensions)) {
                    $size +=  filesize($each);
                } else {
                    $size +=  self::folderSize($each, $extensions);
                }
            } else {
                if (is_file($each)) {
                    $size +=  filesize($each);
                } else {
                    $size +=  self::folderSize($each);
                }
            }

        }
        return $size;
    }

    /**
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    public static function getUnitsOfBytes (int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, \count($units) - 1);

        // Uncomment one of the following alternatives
         $bytes /= 1024 ** $pow;
//         $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * @param Media $media
     * @return bool
     * @throws \Exception
     */
    public function removeMedia(Media $media): bool
    {
        $status = true;
        if (file_exists(storage_path('app/brands/'.$media->getFilePath()))) {
            $status = $status && unlink(storage_path('app/brands/'.$media->getFilePath()));
        }

        if (file_exists(storage_path('app/brands_thumbnail/'.$media->getFilePath()))) {
            $status = $status && unlink(storage_path('app/brands_thumbnail/'.$media->getFilePath()));
        }

        return $status && $media->delete();
    }

    /**
     * @param Request $request
     * @param Brand $brand
     * @return Media
     * @throws \Exception
     */
    public function uploadMedia(Request $request, Brand $brand): Media
    {
        if ($brand->user->cant('upload', Media::class)) {
            throw new \LogicException('You can\'t upload more files');
        }
        $file  = $request->file('file');
        if (self::calculateUsedStorageFull($brand) + $file->getSize() >= $brand->getProduct()->storage) {
            throw new \LogicException('This file bigger than you have the free space');
        }

        $media = new Media([
            'file_name'    => Media::FILE_PREFIX . $file->hashName(),
            'origin_name'  => $file->getClientOriginalName(),
            'content_type' => $file->getMimeType(),
            'file_size'    => $file->getSize(),
            'brand_id'     => $brand->id,
            'dir'          => $brand->getImagePath()
        ]);

        if (\in_array($file->getClientOriginalExtension(), ['ai', 'eps'])) {
            $status = $this->mediaManager->storeMedia($media, $file);
            $image = new \Imagick();
            $image->readImage($file->getRealPath());
            $image->thumbnailImage(640, 480);
            $image->writeImage(storage_path('app/brands_thumbnail/'.$media->brand->id.'/'.$media->file_name.'.png'));
            $media->thumbnail = $media->file_name.'.png';
            $media->width = $image->getImageWidth();
            $media->height = $image->getImageHeight();
        } elseif (\in_array($file->getClientOriginalExtension(), ['mp4'])) {
//            $mp4FileProcessor = new Mp4FileProcessor($this->imageManager);
//            $media = $mp4FileProcessor->getMediaForFile($file->getRealPath(), $brand, $media);

            $media = $this->setVideoData($media, $file, $brand);
            $status = $this->mediaManager->storeMedia($media, $file);


        } else {
            $this->mediaManager->read($file->getRealPath());
            $status = $this->mediaManager->storeMedia($media, $file);
            $media = $this->mediaManager->setSizes($media);
            $status = $status && $this->mediaManager->makeAndStoreThumbnail($media);
            $media->thumbnail = $media->file_name;
        }

        if (!$status) {
            throw new \LogicException('Can\'t upload file');
        }

        try {
            $media->title = $media->getIPTC() ? $media->getIPTC()->getTitle() : '';
        } catch (\Exception $exception) {}
        $media->keywords = $media->getEXIF() && $media->getEXIF()->getKeywords() ? implode(', ', $media->getEXIF()->getKeywords()) : '';
        $media->source = $media->getEXIF() && $media->getEXIF()->getSource() ? $media->getEXIF()->getSource() : '';
        $media->save();

        return $media;
    }

    /**
     * @param \Intervention\Image\Image $image
     * @return \Intervention\Image\Image
     */
    public static function makeThumbnail (\Intervention\Image\Image $image): \Intervention\Image\Image
    {
        return $image->fit(640, 480);
    }

    /**
     * @param Media $media
     * @param $file
     * @return Media
     */
    public function setVideoData (Media $media, $file, $brand)
    {
        $ffmpeg = FFMpeg::create([
            'ffmpeg.binaries' => '/usr/bin/ffmpeg',
            'ffprobe.binaries' => '/usr/bin/ffprobe',
            'timeout' => 3600, // The timeout for the underlying process
            'ffmpeg.threads' => 2, // The number of threads that FFMpeg should use
        ]);

        $video = $ffmpeg->open($file->getRealPath());
        $frameImage = $video->frame(TimeCode::fromSeconds(1));
        $frameImage->save(storage_path('app/brands_thumbnail/'.$brand->id.'/'.$file->getBasename().'.jpg'));
        $media->thumbnail = $file->getBasename().'.jpg';
        $media->width = 0;
        $media->height = 0;
        $media->orientation  = $media->width >= $media->height ? Media::LANDSCAPE : Media::PORTRAIT;
        return $media;
    }
}