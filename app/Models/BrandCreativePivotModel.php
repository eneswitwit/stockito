<?php

namespace App\Models;

use App\Support\Database\CacheQueryBuilder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BrandCreativePivotModel extends Pivot
{

    use CacheQueryBuilder;

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
