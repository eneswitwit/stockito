<?php

namespace App\Console\Commands;

use App\Models\Voucher;
use Stripe\Coupon as StripeCoupon;
use Illuminate\Console\Command;

class RemoveCouponsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:coupons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all coupons';

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
        Voucher::all()->each(function (Voucher $voucher) {
            $voucher->delete();
        });

        do {
            $this->info('Requesting the coupons');
            $stripeCoupons = StripeCoupon::all(['limit' => 100]);
            $stripeCouponsCount = count($stripeCoupons->data);
            foreach ($stripeCoupons->data as $key => $stripeCoupon) {
                /**
                 * @var StripeCoupon $stripeCoupon
                 */
                $this->info('Coupon '.($key+1).' of '.$stripeCouponsCount. ' '.$stripeCoupon->id);
                $stripeCoupon->delete();
            }

        } while ($stripeCoupons->has_more);
    }
}
