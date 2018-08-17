<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Longman\LaravelLodash\Eloquent\UserIdentities;
use Stripe\Coupon;

/**
 * Class Subscriptions
 *
 * @package App\Models
 * @mixin \Eloquent
 * @property int $id
 * @property string $code
 * @property float $sale
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Voucher whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Voucher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Voucher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Voucher whereSale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Voucher whereUpdatedAt($value)
 * @property string|null $duration
 * @property string|null $amount_off
 * @property string|null $currency
 * @property string|null $duration_in_months
 * @property int|null $max_redemptions
 * @property int|null $percent_off
 * @property string|null $redeem_by
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Voucher whereAmountOff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Voucher whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Voucher whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Voucher whereDurationInMonths($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Voucher whereMaxRedemptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Voucher wherePercentOff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Voucher whereRedeemBy($value)
 * @property float $amount
 * @property string $type
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Voucher whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Voucher whereType($value)
 */
class Voucher extends Model
{
    public const AMOUNT_REDUCTION = 'amount';
    public const PERCENTAGE_REDUCTION = 'percentage';

    /**
     * @var array
     */
    public static $typesReduction = [
        self::AMOUNT_REDUCTION,
        self::PERCENTAGE_REDUCTION
    ];

    /**
     * @var array
     */
    public static $typesReductionTitled = [
        self::AMOUNT_REDUCTION => 'Amount',
        self::PERCENTAGE_REDUCTION => 'Percentage'
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'code',
        'amount',
        'type',
        'currency'
    ];

    /**
     * @return Coupon
     */
    public function getStripeCoupon(): Coupon
    {
        return Coupon::retrieve($this->code);
    }
}