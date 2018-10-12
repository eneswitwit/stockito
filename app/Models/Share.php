<?php

// namespace
namespace App\Models;

// use
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Longman\LaravelLodash\Eloquent\UserIdentities;

/**
 * App\Models\Share
 *
 * @property int $id
 * @property string $hash
 * @property string $type
 * @property int $media_id
 * @property int $opened
 * @property string|null $expires_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read \App\Models\User|null $creator
 * @property-read \App\Models\User|null $destroyer
 * @property-read \App\Models\User|null $editor
 * @property-read \App\Models\Media $media
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Share active()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Share whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Share whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Share whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Share whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Share whereHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Share whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Share whereMediaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Share whereOpened($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Share whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Share whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Share whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Media[] $medias
 */
class Share extends Model
{
    use UserIdentities;

    public const LINK_NEVER_EXPIRES = 'LINK_NEVER_EXPIRES';
    public const TIME_LIMITED_LINK = 'TIME_LIMITED_LINK';
    public const ONE_TIME_LINK = 'ONE_TIME_LINK';

    /**
     * @var array
     */
    protected $fillable = [
        'hash',
        'type',
        'media_id',
        'opened',
        'expires_at'
    ];

    /**
     * @return BelongsToMany
     */
    public function medias(): BelongsToMany
    {
        return $this->belongsToMany(Media::class);
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeActive(Builder $builder): Builder
    {
        return $builder->where(function (Builder $builder) {
            $builder->where('type', self::ONE_TIME_LINK)->where('opened', false);
        })->orWhere(function (Builder $builder) {
            $builder->where('type', self::TIME_LIMITED_LINK)->where('expires_at', '>', Carbon::now());
        })->orWhere('type', self::LINK_NEVER_EXPIRES);
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return route('share.image', ['hash' => $this->hash]);
    }

    /**
     * @return array
     */
    public static function getTypes(): array
    {
        return [
            self::TIME_LIMITED_LINK,
            self::ONE_TIME_LINK,
            self::LINK_NEVER_EXPIRES
        ];
    }

}
