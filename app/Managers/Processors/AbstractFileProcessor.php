<?php

namespace App\Managers\Processors;

use App\Models\Brand;
use App\Models\Media;
use Illuminate\Http\File;

abstract class AbstractFileProcessor implements FileProcessorInterface
{
    /**
     * @var File
     */
    protected $file;

    /**
     * @param string $filePath
     * @param Brand $brand
     * @param Media|null $media
     * @return Media
     */
    public function getMediaForFile(string $filePath, Brand $brand, Media $media = null): Media
    {
        $this->file = new File($filePath);

        if ($media === null) {
            $media = new Media();
        }

        $media->file_size = $this->file->getSize();
        $media->content_type = $this->file->getMimeType();
        $media->file_name = $this->file->getFilename();
        $media->origin_name = $this->file->getFilename();
        $media->brand()->associate($brand);
        $media->dir = $brand->id;

        return $media;
    }
}
