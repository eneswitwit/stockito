<?php

namespace App\Models\Media;

use App\Models\Brand;
use App\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Longman\LaravelLodash\Eloquent\UserIdentities;

/**
 * App\Models\Media\Category
 *
 * @property int $id
 * @property string $name
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Category whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Category whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Category whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Media[] $medias
 * @property int $brand_id
 * @property-read \App\Models\Brand $brand
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Category whereBrandId($value)
 * @property-read \App\Models\User|null $creator
 * @property-read \App\Models\User|null $destroyer
 * @property-read \App\Models\User|null $editor
 */
class Category extends Model
{
    use UserIdentities;

    /**
     * @var array
     */
    protected $fillable = ['name', 'brand_id'];

    /**
     * @return HasMany
     */
    public function medias (): HasMany
    {
        return $this->hasMany(Media::class);
    }

    /**
     * @return BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
}
