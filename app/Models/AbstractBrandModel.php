<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphMany;

abstract class AbstractBrandModel extends AbstractUserType implements OriginActivityInterface, TargetActivityInterface
{
    /**
     * @return MorphMany
     */
    public function originActivity(): MorphMany
    {
        return $this->morphMany(Activity::class, 'origin');
    }

    /**
     * @return MorphMany
     */
    public function targetActivity(): MorphMany
    {
        return $this->morphMany(Activity::class, 'target');
    }
}