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

/**
 * Class LicenseChangedForMediaEvent
 * @package App\Events
 */
class LicenseChangedForMediaEvent extends AbstractActivityEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var int
     */
    public $type = Activity::LICENSE_TYPE;

    /**
     * @var OriginActivityInterface
     */
    public $origin;

    /**
     * @var TargetActivityInterface
     */
    public $target;

    /**
     * @var Brand
     */
    public $brand;

    /**
     * LicenseChangedForMediaEvent constructor.
     * @param OriginActivityInterface $origin
     * @param Media $target
     */
    public function __construct(OriginActivityInterface $origin, Media $target)
    {
        $this->origin = $origin;
        $this->target = $target;
        $this->brand = $target->brand;
    }

    /**
     * @return OriginActivityInterface
     */
    public function getOrigin () : OriginActivityInterface
    {
        return $this->origin;
    }

    /**
     * @return TargetActivityInterface
     */
    public function getTarget () : TargetActivityInterface
    {
        return $this->target;
    }

    /**
     * @return Brand
     */
    public function getBrand(): Brand
    {
        return $this->brand;
    }
}
