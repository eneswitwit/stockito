<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Longman\LaravelLodash\Eloquent\UserIdentities;

/**
 * App\Models\Invite
 *
 * @property-read \App\Models\Brand $brand
 * @property-read \App\Models\User $creator
 * @property-read \App\Models\User $destroyer
 * @property-read \App\Models\User $editor
 * @mixin \Eloquent
 * @property int $id
 * @property int $brand_id
 * @property string $email
 * @property string $hash
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invite whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invite whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invite whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invite whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invite whereHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invite whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invite whereUpdatedBy($value)
 */
class Invite extends Model
{
    use UserIdentities;

    /**
     * @return BelongsTo
     */
    public function brand () : BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
}
