<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BrandCreativePivotModel extends Pivot
{
    /**
     * @var string
     */
    protected $table = 'brand_creative';

    /**
     * @return BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * @return BelongsTo
     */
    public function creative(): BelongsTo
    {
        return $this->belongsTo(Creative::class);
    }
}
