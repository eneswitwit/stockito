<?php

namespace App\Managers\Processors;

use App\Models\Brand;
use App\Models\Media;

class EPSFileProcessor extends AbstractFileProcessor
{
    /**
     * @var \Imagick
     */
    protected $image;

    /**
     * @param string $filePath
     * @param Brand $brand
     * @param Media|null $media
     * @return Media
     * @throws \ImagickException
     */
    public function getMediaForFile(string $filePath, Brand $brand, Media $media = null): Media
    {
        $media = parent::getMediaForFile($filePath, $brand, $media);

        $this->image = new \Imagick($filePath);

        $media->width = $this->image->getImageWidth();
        $media->height = $this->image->getImageHeight();
        $media->orientation  = $media->width < $media->height ? Media::PORTRAIT : Media::LANDSCAPE;

        try {
            $media->title = $media->getIPTC() ? $media->getIPTC()->getTitle() : '';
        } catch (\Exception $exception) {}

        $media->keywords = $media->getEXIF() && $media->getEXIF()->getKeywords() ? implode(', ', $media->getEXIF()->getKeywords()) : '';
        $media->source = $media->getEXIF() && $media->getEXIF()->getSource() ? $media->getEXIF()->getSource() : '';

        $media = $this->makeThumbnail($media);

        return $media;
    }

    /**
     * @param Media $media
     * @return Media
     */
    protected function makeThumbnail(Media $media): Media
    {
        $image = clone $this->image;
        $image->thumbnailImage(800, 0);
        $image->writeImage(storage_path('app/brands_thumbnail/'.$media->brand->id.'/'.$this->file->getFilename().'.png'));
        $media->thumbnail = $this->file->getFilename().'.png';
        return $media;
    }
}
