<?php

// namespace
namespace App\Managers\Processors;

// use
use App\Models\Brand;
use App\Models\Media;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use Illuminate\Http\File;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;

/**
 * Class Mp4FileProcessor
 *
 * @package App\Managers\Processors
 */
class Mp4FileProcessor extends AbstractFileProcessor
{
    /**
     * @var ImageManager
     */
    protected $imageManager;

    /**
     * @var Image|null
     */
    protected $image;

    /**
     * ImageFileProcessor constructor.
     * @param ImageManager $imageManager
     */
    public function __construct(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
    }

    /**
     * @param string $filePath
     * @param Brand $brand
     * @param Media|null $media
     * @return Media
     */
    public function getMediaForFile(string $filePath, Brand $brand, Media $media = null): Media
    {
        $media = parent::getMediaForFile($filePath, $brand, $media);
        $media = $this->makeThumbnail($media, $this->file, $brand);

        return $media;
    }

    /**
     * @param Media $media
     * @param File $file
     * @param Brand $brand
     * @return Media
     */
    private function makeThumbnail(Media $media, File $file, Brand $brand): Media
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
