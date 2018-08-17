<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Longman\LaravelLodash\Eloquent\UserIdentities;

/**
 * App\Models\PeopleAttribute
 *
 * @property int $id
 * @property string $name
 * @property int $brand_id
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Brand $brand
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Media[] $medias
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PeopleAttribute whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PeopleAttribute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PeopleAttribute whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PeopleAttribute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PeopleAttribute whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PeopleAttribute whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PeopleAttribute whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property-read \App\Models\User|null $creator
 * @property-read \App\Models\User $destroyer
 * @property-read \App\Models\User|null $editor
 */
class PeopleAttribute extends Model
{
    use UserIdentities;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'brand_id'
    ];

    /**
     * @return BelongsToMany
     */
    public function medias (): BelongsToMany
    {
        return $this->belongsToMany(Media::class);
    }

    /**
     * @return BelongsTo
     */
    public function brand (): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
}
