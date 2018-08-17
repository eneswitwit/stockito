<?php

namespace App\Classes;

class ImageMetadataParser extends \ImageMetadataParser
{
    /**
     * @return array
     */
    public function all()
    {
        return $this->aAttributes;
    }
}