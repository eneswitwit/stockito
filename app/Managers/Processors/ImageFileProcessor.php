<?php

namespace App\Managers\Processors;

use App\Managers\MediaManager;
use App\Models\Brand;
use App\Models\Media;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;

class ImageFileProcessor extends AbstractFileProcessor
{
    /**
     * @var ImageManager
     */
    protected $imageManager;

    /**
     * @var MediaManager
     */
    protected $mediaManager;

    /**
     * @var Image|null
     */
    protected $image;

    /**
     * ImageFileProcessor constructor.
     * @param ImageManager $imageManager
     * @param MediaManager $mediaManager
     */
    public function __construct(ImageManager $imageManager, MediaManager $mediaManager)
    {
        $this->imageManager = $imageManager;
        $this->mediaManager = $mediaManager;
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

        $this->image = $this->imageManager->make($filePath);

        $media->width = $this->image->width();
        $media->height = $this->image->height();
        $media->orientation  = $media->width < $media->height ? Media::PORTRAIT : Media::LANDSCAPE;

        try {
            $media->title = $media->getIPTC() ? $media->getIPTC()->getTitle() : '';
        } catch (\Exception $exception) {}

        $media->keywords = $media->getEXIF() && $media->getEXIF()->getKeywords() ? implode(', ', $media->getEXIF()->getKeywords()) : '';
        $media->source = $media->getEXIF() && $media->getEXIF()->getSource() ? $media->getEXIF()->getSource() : '';

        $this->mediaManager->read($filePath);

        $media = $this->makeThumbnail($media);

        return $media;
    }

    /**
     * @param Media $media
     * @return Media
     */
    protected function makeThumbnail(Media $media): Media
    {
        $this->mediaManager->makeAndStoreThumbnail($media);
        $media->thumbnail = $media->file_name;
        return $media;
    }
}
