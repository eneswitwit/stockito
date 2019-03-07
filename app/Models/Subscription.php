<?php

// namespace
namespace App\Models;

// use
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Stripe\Subscription as StripeSubscription;
use App\Support\Database\CacheQueryBuilder;


/**
 * App\Models\Subscription
 *
 * @property int $id
 * @property string $stripe_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int $user_id
 * @property string $name
 * @property string $stripe_plan
 * @property int $quantity
 * @property string|null $trial_ends_at
 * @property string|null $ends_at
 * @property string|null $downgrade_to_stripe_plan
 *
 * @property-read \App\Models\Plan $plan
 * @property-read \App\Models\User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Subscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Subscription whereEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Subscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Subscription whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Subscription whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Subscription whereStripeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Subscription whereStripePlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Subscription whereTrialEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Subscription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Subscription whereUserId($value)
 *
 * @mixin \Eloquent
 */
class Subscription extends Model
{

    use CacheQueryBuilder;

    /**
     * @var array
     */
    protected $fillable = [
        'stripe_id',
        'created_at',
        'updated_at',
        'user_id',
        'name',
        'stripe_plan',
        'quantity',
        'trial_ends_at',
        'ends_at',
        'downgrade_to_stripe_plan'
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasOne
     */
    public function plan(): HasOne
    {
        return $this->hasOne(Plan::class, 'stripe_id', 'stripe_plan');
    }

    /**
     * @return StripeSubscription
     */
    public function getStripeSubscription(): StripeSubscription
    {
        return StripeSubscription::retrieve($this->stripe_id);
    }

    /**
     * @param string $planId
     *
     * @return void
     */
    public function downgradeToPlan($stripePlanId): void
    {
        $this->downgrade_to_stripe_plan = $stripePlanId;
        $this->save();
    }

    /**
     * @return void
     */
    public function cancelDowngrade(): void
    {
        $this->downgrade_to_stripe_plan = null;
        $this->save();
    }


}
