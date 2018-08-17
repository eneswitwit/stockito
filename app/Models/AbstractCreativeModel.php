<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphMany;

abstract class AbstractCreativeModel extends AbstractUserType implements OriginActivityInterface
{
    /**
     * @return MorphMany
     */
    public function originActivity(): MorphMany
    {
        return $this->morphMany(Activity::class, 'origin');
    }
}