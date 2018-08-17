<?php

namespace App\Events;

use App\Models\Activity;
use App\Models\Brand;
use App\Models\Creative;
use App\Models\OriginActivityInterface;
use App\Models\TargetActivityInterface;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class CreativeJoinedToBrandEvent extends AbstractActivityEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var int
     */
    public $type = Activity::MANAGE_CREATIVE_TYPE;

    /**
     * @var Creative
     */
    public $creative;

    /**
     * @var Brand
     */
    public $brand;

    /**
     * CreativeJoinedToBrandEvent constructor.
     * @param Creative $creative
     * @param Brand $brand
     */
    public function __construct(Creative $creative, Brand $brand)
    {
        $this->creative = $creative;
        $this->brand = $brand;
    }

    /**
     * @return OriginActivityInterface
     */
    public function getOrigin () : OriginActivityInterface
    {
        return $this->creative;
    }

    /**
     * @return TargetActivityInterface
     */
    public function getTarget () : TargetActivityInterface
    {
        return $this->brand;
    }

    /**
     * @return Brand
     */
    public function getBrand(): Brand
    {
        return $this->brand;
    }
}
