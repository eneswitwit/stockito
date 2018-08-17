<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * App\Models\Activity
 *
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent|OriginActivityInterface $origin
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent|TargetActivityInterface $target
 * @mixin \Eloquent
 * @property-read \App\Models\Brand $brand
 * @property int $id
 * @property int $type
 * @property int $target_id
 * @property string $target_type
 * @property int $origin_id
 * @property string $origin_type
 * @property int $brand_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static Builder|Activity whereBrandId($value)
 * @method static Builder|Activity whereCreatedAt($value)
 * @method static Builder|Activity whereId($value)
 * @method static Builder|Activity whereOriginId($value)
 * @method static Builder|Activity whereOriginType($value)
 * @method static Builder|Activity whereTargetId($value)
 * @method static Builder|Activity whereTargetType($value)
 * @method static Builder|Activity whereType($value)
 * @method static Builder|Activity whereUpdatedAt($value)
 * @property string $message
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity whereMessage($value)
 */
class Activity extends Model
{
    public const LICENSE_TYPE = 1;
    public const UPLOAD_MEDIA_TYPE = 2;
    public const EDIT_MEDIA_TYPE = 3;
    public const DELETE_MEDIA_TYPE = 4;
    public const MANAGE_CREATIVE_TYPE = 5;

    /**
     * @var array
     */
    protected $fillable = ['message', 'brand_id'];

    /**
     * @return MorphTo
     */
    public function origin(): MorphTo
    {
        return $this->morphTo('origin')->withTrashed();
    }

    /**
     * @return MorphTo
     */
    public function target(): MorphTo
    {
        return $this->morphTo('target')->withTrashed();
    }

    /**
     * @return BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->message;
    }
}
