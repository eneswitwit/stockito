<?php

namespace App\Console\Commands;

use App\Models\Voucher;
use Stripe\Coupon as StripeCoupon;
use Illuminate\Console\Command;
use Stripe\Stripe;

class SyncCouponsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:coupons {--f|force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync coupons with Stripe';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        if ($this->hasOption('force')) {
            Voucher::truncate();
        }

        $stripeCouponsIds = [];

        $lastStripeCouponId = null;
        do {
            $this->info('Requesting the coupons');
            $stripeCoupons = StripeCoupon::all(['limit' => 100, 'starting_after' => $lastStripeCouponId]);
            $stripeCouponsCount = count($stripeCoupons->data);
            foreach ($stripeCoupons->data as $key => $stripeCoupon) {
                $this->info('Product '.($key+1).' of '.$stripeCouponsCount. ' '.$stripeCoupon->id);
                $stripeCouponsIds[] = $stripeCoupon->id;

                $coupon = (new Voucher())->where('code', $stripeCoupon->id)->first();

                if (!$coupon) {
                    Voucher::create([
                        'code' => $stripeCoupon->id,
                        'amount' => $stripeCoupon->amount_off ? $stripeCoupon->amount_off / 100 : $stripeCoupon->percent_off,
                        'type' => $stripeCoupon->amount_off !== null ? Voucher::AMOUNT_REDUCTION : Voucher::PERCENTAGE_REDUCTION,
                        'currency' => $stripeCoupon->currency
                    ]);
                }

                $lastStripeCouponId = $stripeCoupon->id;
            }

        } while ($stripeCoupons->has_more);

        (new Voucher())->whereNotIn('code', $stripeCouponsIds)->delete();
    }
}
