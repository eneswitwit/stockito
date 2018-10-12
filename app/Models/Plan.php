<?php

// namespace
namespace App\Models;

// use
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Stripe\Plan as StripePlan;

/**
 * App\Models\Plan
 *
 * @property int $id
 * @property string $currency
 * @property float $price
 * @property string $stripe_id
 * @property string $interval
 * @property int $product_id
 * @property int|null $ftp_group_id
 *
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * @const string DAY_INTERVAL
 * @const string MONTH_INTERVAL
 * @const string YEAR_INTERVAL
 * @const string CURRENCY_USD
 * @const string CURRENCY_EUR
 * @const string CURRENCY_EUR_SYMBOL
 * @const string CURRENCY_USD_SYMBOL
 *
 * @property-read \App\Models\FTPGroup|null $ftpGroup
 * @property-read \Stripe\Plan $plan
 * @property-read \App\Models\Plan $planForDowngrade
 * @property-read \App\Models\Plan|null $planForUpgrade
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read \App\Models\Product $product
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Invoice[] $invoices
 *
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Plan whereProductId($value)
 */
class Plan extends Model
{

    public const DAY_INTERVAL = 'day';
    public const MONTH_INTERVAL = 'month';
    public const YEAR_INTERVAL = 'year';

    public const CURRENCY_USD = 'usd';
    public const CURRENCY_EUR = 'eur';

    public const CURRENCY_USD_SYMBOL = '$';
    public const CURRENCY_EUR_SYMBOL = '€';

    /**
     * @var array
     */
    protected $attributes = [
        'currency' => self::CURRENCY_EUR
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
        'interval',
        'price',
        'ftp_group_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return \Stripe\Plan
     */
    public function getStripePlan(): StripePlan
    {
        return StripePlan::retrieve($this->stripe_id);
    }

    /**
     * @return array
     */
    public static function getIntervalTitles(): array
    {
        return [
            self::MONTH_INTERVAL => 'Month',
            self::DAY_INTERVAL => 'Day',
            self::YEAR_INTERVAL => 'Year'
        ];
    }

    /**
     * @return array
     */
    public static function getCurrencySymbols(): array
    {
        return [
            self::CURRENCY_USD => self::CURRENCY_USD_SYMBOL,
            self::CURRENCY_EUR => self::CURRENCY_EUR_SYMBOL
        ];
    }

    /**
     * @return null|string
     */
    public function getIntervalTitle(): ?string
    {
        return Arr::get(self::getIntervalTitles(), $this->getStripePlan()->interval, null);
    }

    /**
     * @return null|string
     */
    public function getCurrencySymbol(): ?string
    {
        return Arr::get(self::getCurrencySymbols(), $this->currency, '€');
    }

    /**
     * @return \Stripe\Plan
     */
    public function getPlanAttribute(): \Stripe\Plan
    {
        return $this->getStripePlan();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function planForUpgrade(): BelongsTo
    {
        return $this->belongsTo(__CLASS__);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}