<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OAuthProvider
 *
 * @property int $id
 * @property int $user_id
 * @property string $provider
 * @property string $provider_user_id
 * @property string|null $access_token
 * @property string|null $refresh_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static Builder|OAuthProvider whereAccessToken($value)
 * @method static Builder|OAuthProvider whereCreatedAt($value)
 * @method static Builder|OAuthProvider whereId($value)
 * @method static Builder|OAuthProvider whereProvider($value)
 * @method static Builder|OAuthProvider whereProviderUserId($value)
 * @method static Builder|OAuthProvider whereRefreshToken($value)
 * @method static Builder|OAuthProvider whereUpdatedAt($value)
 * @method static Builder|OAuthProvider whereUserId($value)
 * @mixin \Eloquent
 */
class OAuthProvider extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'oauth_providers';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'access_token', 'refresh_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
