<?php

namespace App\Events;

use App\Models\Brand;
use App\Models\OriginActivityInterface;
use App\Models\TargetActivityInterface;

/**
 * Interface ActivityEventInterface
 * @package App\Events
 */
interface ActivityEventInterface
{
    /**
     * @return OriginActivityInterface
     */
    public function getOrigin () : OriginActivityInterface;

    /**
     * @return TargetActivityInterface
     */
    public function getTarget () : TargetActivityInterface;

    /**
     * @return Brand
     */
    public function getBrand () : Brand;
}