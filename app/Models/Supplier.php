<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Supplier
 *
 * @property-read \App\Models\Brand $brand
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property int $brand_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereUpdatedAt($value)
 */
class Supplier extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'brand_id'];

    /**
     * @return BelongsTo
     */
    public function brand (): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
}
