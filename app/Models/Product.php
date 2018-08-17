<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Stripe\Product as StripProduct;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $stripe_id
 * @property string $name
 * @property int|null $product_for_update_id
 * @property float $storage
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Plan[] $plans
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereProductForUpdateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereStorage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereStripeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $ftp_group_id
 * @property-read \App\Models\FTPGroup|null $ftpGroup
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereFtpGroupId($value)
 */
class Product extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'stripe_id',
        'name',
        'storage',
        'product_for_update_id'
    ];

    /**
     * @return StripProduct
     */
    public function getStripeProduct(): StripProduct
    {
        return StripProduct::retrieve($this->stripe_id);
    }

    /**
     * @return HasMany
     */
    public function plans (): HasMany
    {
        return $this->hasMany(Plan::class);
    }

    /**
     * @return BelongsTo
     */
    public function ftpGroup (): BelongsTo
    {
        return $this->belongsTo(FTPGroup::class, 'ftp_group_id');
    }

    /**
     * @return int
     */
    public function getCountBrands(): int
    {
        return Brand::whereIn('plan_id', $this->plans()->pluck('id')->toArray())->count();
    }

    /**
     * @return int
     */
    public function getUsedStorage(): int
    {
        return Brand::whereIn('plan_id', $this->plans()->pluck('id')->toArray())->sum('used_storage_photo');
    }
}
