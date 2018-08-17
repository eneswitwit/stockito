<?php

namespace App\Events;

use App\Models\Activity;
use App\Models\Brand;
use App\Models\Media;
use App\Models\OriginActivityInterface;
use App\Models\TargetActivityInterface;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class UploadedFileEvent extends AbstractActivityEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var int
     */
    public $type = Activity::UPLOAD_MEDIA_TYPE;

    /**
     * @var Media
     */
    public $media;

    /**
     * @var Brand
     */
    public $brand;

    /**
     * UploadedFileEvent constructor.
     * @param Media $media
     * @param Brand $brand
     */
    public function __construct(Media $media, Brand $brand)
    {
        $this->media = $media;
        $this->brand = $brand;
    }

    /**
     * @return OriginActivityInterface
     */
    public function getOrigin () : OriginActivityInterface
    {
        return $this->brand;
    }

    /**
     * @return TargetActivityInterface
     */
    public function getTarget () : TargetActivityInterface
    {
        return $this->media;
    }

    /**
     * @return Brand
     */
    public function getBrand(): Brand
    {
        return $this->brand;
    }
}
