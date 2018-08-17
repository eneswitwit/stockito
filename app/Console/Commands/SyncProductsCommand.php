<?php

namespace App\Console\Commands;

use App\Models\FTPGroup;
use App\Models\FTPQuotaLimits;
use App\Models\Plan;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Stripe\Plan as StripePlan;
use Stripe\Product as StripeProduct;
use Illuminate\Console\Command;
use Stripe\Stripe;

class SyncProductsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:products {--f|force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync products with Stripe';

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * SyncProductsCommand constructor.
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
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
            Product::all()->each(function (Product $product) {
                $product->delete();
            });
            Plan::all()->each(function (Plan $plan) {
                $plan->delete();
            });
            FTPGroup::all()->each(function (FTPGroup $FTPGroup) {
                $FTPGroup->delete();
            });
            FTPQuotaLimits::all()->each(function (FTPQuotaLimits $FTPQuotaLimits) {
                $FTPQuotaLimits->delete();
            });
        }

        $stripeProductsIds = [];

        $lastStripeProductId = null;
        do {
            $this->info('Requesting the products');
            $stripeProducts = StripeProduct::all(['limit' => 100, 'type' => 'service','starting_after' => $lastStripeProductId]);
            $stripeProductsCount = count($stripeProducts->data);
            foreach ($stripeProducts->data as $key => $stripeProduct) {
                $this->info('Product '.($key+1).' of '.$stripeProductsCount. ' '.$stripeProduct->id);
                $stripeProductsIds[] = $stripeProduct->id;

                $product = (new Product)->where('stripe_id', $stripeProduct->id)->first();

                if (!$product) {
                    $product = Product::create([
                        'stripe_id' => $stripeProduct->id,
                        'name' => $stripeProduct->name,
                        'storage' => $stripeProduct->metadata->storage ?? 0
                    ]);
                    $this->productRepository->createQuotaLimits($product);
                }

                $stripePlansIds = [];

                $lastStripePlanId = null;
                do {
                    $stripePlans = StripePlan::all(['product' => $stripeProduct->id, 'limit' => 100, 'starting_after' => $lastStripePlanId]);

                    foreach ($stripePlans->data as $stripePlan) {
                        $stripePlansIds[] = $stripePlan->id;

                        $plan = $product->plans()->where('stripe_id', $stripePlan->id)->first();
                        if (!$plan) {
                            Plan::create([
                                'stripe_id' => $stripePlan->id,
                                'product_id' => $product->id,
                                'price' => $stripePlan->amount / 100,
                                'currency' => $stripePlan->currency,
                                'interval' => $stripePlan->interval
                            ]);
                        }

                        $lastStripePlanId = $stripePlan->id;
                    }
                } while ($stripePlans->has_more);

                $product->plans()->whereNotIn('stripe_id', $stripePlansIds)->delete();
                $lastStripeProductId = $stripeProduct->id;
            }

        } while ($stripeProducts->has_more);

        (new Product())->whereNotIn('stripe_id', $stripeProductsIds)->delete();
    }
}
