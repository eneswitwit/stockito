<?php

// namespace
namespace App\Models;

// use
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stripe\Invoice as StripeInvoice;

/**
 * Class Invoice
 *
 * @package App\Models
 * @property int $id
 * @property string|null $number
 * @property string|null $stripe_id
 * @property int|null $amount
 * @property string|null $currency
 * @property string|null $customer
 * @property string|null $customer_email
 * @property string|null $date
 * @property string|null $description
 * @property string|null $invoice
 * @property string|null $livemode
 * @property string|null $period_start
 * @property string|null $period_end
 * @property string|null $plan
 * @property string|null $proration
 * @property int|null $quantity
 * @property string|null $subscription
 * @property int|null $unit_amount
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereCustomerEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereInvoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereLivemode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice wherePeriodEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice wherePeriodStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice wherePlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereProration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereStripeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereSubscription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereUnitAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $brand_id
 * @property-read \App\Models\Brand|null $brand
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereBrandId($value)
 * @property int $paid
 * @property int|null $plan_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice wherePaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice wherePlanId($value)
 * @property-read \App\Models\Plan|null $plan1
 * @property-read \App\Models\User|null $user
 */
class Invoice extends Model
{
    /**
     * @var string
     */
    protected $table = 'invoices';

    /**
     * @var array
     */
    protected $fillable = [
        'number',
        'stripe_id',
        'amount',
        'currency',
        'customer',
        'customer_email',
        'date',
        'description',
        'livemode',
        'period_start',
        'period_end',
        'brand_id',
        'plan_id'
    ];

    /**
     * @return BelongsTo
     */
    public function brand (): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * @return BelongsTo
     */
    public function plan1(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'id');
    }

    /**
     * @return mixed
     */
    public function getPlanAttribute ()
    {
        $this->load('plan1');
        return $this->getRelations()['plan1'];
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer', 'stripe_id');
    }

    /**
     * @return StripeInvoice
     */
    public function getStripeObject (): StripeInvoice
    {
        return StripeInvoice::retrieve($this->stripe_id);
    }

    /**
     * @return string
     */
    public function getFileName (): string
    {
        return 'invoice-'.$this->id.'.pdf';
    }

}