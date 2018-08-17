<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Arr;
use Stripe\Plan as StripePlan;

/**
 * App\Models\Plan
 *
 * @property int $id
 * @property string $title
 * @property string $currency
 * @property float $price
 * @property string $stripe_id
 * @property string $interval
 * @property float $storage
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property float $video_storage
 * @property int|null $ftp_group_id
 * @property int|null $plan_for_upgrade_id
 * @property-read \App\Models\FTPGroup|null $ftpGroup
 * @property-read \Stripe\Plan $plan
 * @property-read \App\Models\Plan $planForDowngrade
 * @property-read \App\Models\Plan|null $planForUpgrade
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Plan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Plan whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Plan whereFtpGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Plan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Plan whereInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Plan wherePlanForUpgradeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Plan wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Plan whereStorage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Plan whereStripeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Plan whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Plan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Plan whereVideoStorage($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Invoice[] $invoices
 * @property int $product_id
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Plan whereProductId($value)
 */
class Plan extends Model
{
    public const DAY_INTERVAL = 'day';
    public const MONTH_INTERVAL = 'month';

    public const CURRENCY_USD = 'usd';
    public const CURRENCY_EUR = 'eur';

    public const CURRENCY_USD_SYMBOL = '$';
    public const CURRENCY_EUR_SYMBOL = '€';

    /**
     * @var array
     */
    protected $attributes = [
        'currency' => self::CURRENCY_USD
    ];

    /**
     * @var array
     */
    protected $dates = ['ends_at', 'trial_ends_at'];

    /**
     * @var array
     */
    protected $fillable = [
        'product_id',
        'stripe_id',
        'title',
        'interval',
        'price'
    ];
    /**
     * @return BelongsToMany
     */
    public function users (): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return StripePlan
     */
    public function getStripePlan (): StripePlan
    {
        return StripePlan::retrieve($this->stripe_id);
    }

    /**
     * @return array
     */
    public static function getIntervalTitles (): array
    {
        return [
            self::MONTH_INTERVAL => 'Month',
            self::DAY_INTERVAL => 'Day',
        ];
    }

    /**
     * @return array
     */
    public static function getCurrencySymbols (): array
    {
        return [
            self::CURRENCY_USD => self::CURRENCY_USD_SYMBOL,
            self::CURRENCY_EUR => self::CURRENCY_EUR_SYMBOL
        ];
    }

    /**
     * @return string
     */
    public function getIntervalTitle () : ?string
    {
        return Arr::get(self::getIntervalTitles(), $this->getStripePlan()->interval, null);
    }

    /**
     * @return null|string
     */
    public function getCurrencySymbol() : ?string
    {
        return Arr::get(self::getCurrencySymbols(), $this->currency, null);
    }

    /**
     * @return \Stripe\Plan
     */
    public function getPlanAttribute (): \Stripe\Plan
    {
        return $this->getStripePlan();
    }

    /**
     * @return BelongsTo
     */
    public function planForUpgrade(): BelongsTo
    {
        return $this->belongsTo(__CLASS__);
    }


    /**
     * @return HasMany
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}