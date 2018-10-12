<?php

// namespace
namespace App\Models;


// use
use App\Notifications\MailUserRegistration;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Cashier\Billable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword as ResetPasswordNotification;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $password
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $agree_newsletter
 * @property \Carbon\Carbon|null $agree_privacy_policy
 *
 * @property string|null $stripe_id
 * @property string|null $card_brand
 * @property string|null $card_last_four
 * @property string|null $trial_ends_at
 *
 * @property int $enabled
 * @property string|null $confirmation_token
 * @property int|null $plan_id
 * @property string|null $last_login
 *
 * @property-read \App\Models\Brand $brand
 * @property-read \App\Models\Creative $creative
 * @property-read string $photo_url
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[]
 *     $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OAuthProvider[] $oauthProviders
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Cashier\Subscription[] $subscriptions
 * @property-read \App\Models\Plan|null $plan
 *
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereUpdatedAt($value)
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCardBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCardLastFour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereStripeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereTrialEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereLastLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereConfirmationToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEnabled($value)
 *
 * @mixin \Eloquent
 *
 */
class User extends Authenticatable implements JWTSubject
{
    // use
    use Notifiable;
    use Billable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'photo_url',
    ];

    /**
     * Get the profile photo URL attribute.
     *
     * @return string
     */
    public function getPhotoUrlAttribute(): string
    {
        return 'https://www.gravatar.com/avatar/' . md5(strtolower($this->email)) . '.jpg?s=200&d=mm';
    }

    /**
     * Get the oauth providers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function oauthProviders()
    {
        return $this->hasMany(OAuthProvider::class);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Send mail verification.
     *
     * @param  string $token
     *
     * @return void
     */
    public function sendConfirmationEmail($token)
    {
        $this->notify(new MailUserRegistration($token));
    }

    /**
     * @return int
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * @return HasOne
     */
    public function brand(): HasOne
    {
        return $this->hasOne(Brand::class);
    }

    /**
     * @return HasOne
     */
    public function creative(): HasOne
    {
        return $this->hasOne(Creative::class);
    }

    /**
     * @return AbstractUserType
     */
    public function getType(): ?AbstractUserType
    {
        return $this->brand ?: $this->creative;
    }

    /**
     * @return Activity[]|Collection|static
     */
    public function getActivities()
    {
        $activities = new Collection();

        if ($this->brand) {
            $activities = $this->brand->activities()->orderBy('created_at', 'DESC')->get();
        } elseif ($this->creative) {
            foreach ($this->creative->brands as $brand) {
                $activities = $activities->merge($brand->activities()->orderBy('created_at', 'DESC')->get());
            }
        }

        return $activities;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->getType()->name ?? '';
    }

    /**
     * @return BelongsTo
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getInvoices(): \Illuminate\Support\Collection
    {
        return Invoice::where('customer', $this->stripe_id)->get();
    }
}
