<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Creative
 *
 * @mixin \Eloquent
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Brand[] $brands
 * @property int $id
 * @property int $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $company
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static Builder|Creative whereCompany($value)
 * @method static Builder|Creative whereCreatedAt($value)
 * @method static Builder|Creative whereFirstName($value)
 * @method static Builder|Creative whereId($value)
 * @method static Builder|Creative whereLastName($value)
 * @method static Builder|Creative whereUpdatedAt($value)
 * @method static Builder|Creative whereUserId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Activity[] $originActivity
 * @property-read string $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Activity[] $activities
 */
class Creative extends AbstractCreativeModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'company',
    ];

    /**
     * @return BelongsToMany
     */
    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class)->withPivot('role', 'position');
    }

	/**
	 * @return BelongsTo
	 */
    public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	/**
     * @return string
     */
    public function getNameAttribute($value): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * @return string
     */
    public function originActivityText(): string
    {
        return $this->name;
    }

	/**
	 * @return HasMany
	 */
	public function activities () : HasMany
	{
		return $this->hasMany(Activity::class);
	}

    /**
     * @return string
     */
    public function getUserTypeName(): string
    {
        return 'Creative';
    }
}
