<?php

namespace App\Console\Commands;

use App\Models\FTPGroup;
use App\Models\FTPQuotaLimits;
use App\Models\Plan;
use App\Models\Product;
use Stripe\Plan as StripePlan;
use Stripe\Product as StripeProduct;
use Illuminate\Console\Command;

class RemoveProductsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all products';

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
        Plan::all()->each(function (Plan $plan) {
            $plan->delete();
        });
        Product::all()->each(function (Product $product) {
            $product->delete();
        });

        do {
            $this->info('Requesting the products');
            $stripeProducts = StripeProduct::all(['limit' => 100, 'type' => 'service']);
            $stripeProductsCount = count($stripeProducts->data);
            foreach ($stripeProducts->data as $key => $stripeProduct) {
                /**
                 * @var StripeProduct $stripeProduct
                 */
                $this->info('Product '.($key+1).' of '.$stripeProductsCount. ' '.$stripeProduct->id);

                do {
                    $stripePlans = StripePlan::all(['product' => $stripeProduct->id, 'limit' => 100]);

                    foreach ($stripePlans->data as $stripePlan) {
                        /**
                         * @var StripePlan $stripePlan
                         */
                        $stripePlan->delete();
                    }
                } while ($stripePlans->has_more);

                $stripeProduct->delete();
            }

        } while ($stripeProducts->has_more);
    }
}
