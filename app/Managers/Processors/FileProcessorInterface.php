<?php

namespace App\Managers\Processors;

use App\Models\Brand;
use App\Models\Media;

interface FileProcessorInterface
{
    /**
     * @param string $filePath
     * @param Brand $brand
     * @param Media|null $media
     * @return Media
     */
    public function getMediaForFile(string $filePath, Brand $brand, Media $media = null): Media;
}
