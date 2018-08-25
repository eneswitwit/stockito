<?php

namespace App\Repositories;

use App\Models\FTPGroup;
use App\Models\FTPQuotaLimits;
use App\Models\Plan;
use App\Models\Product;
use App\Repositories\Contacts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Stripe\Product as StripeProduct;
use Stripe\Plan as StripePlan;

/**
 * Class ProductRepository
 *
 * @package App\Repositories
 */
class ProductRepository implements ProductRepositoryInterface
{
    public const CURRENCY = 'usd';
    public const INTERVAL = ['day', 'week', 'month', 'year'];

    /**
     * @var Plan
     */
    protected $product;

    /**
     * ProductRepository constructor.
     * @param Plan $product
     */
    public function __construct(Plan $product = null)
    {
        $this->product = $product;
    }

    /**
     * @return array
     */
    public function interval(): array
    {
        return self::INTERVAL;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return Plan::all();
    }

    /**
     * @return Builder
     */
    public function query() : Builder
    {
        return Plan::query();
    }

    /**
     * @param $price
     *
     * @return int
     */
    protected function convertPrice($price): int
    {
        return (float)($price * 100);
    }

    /**
     * @param Product $product
     * @param int $amount
     * @param string $interval
     * @return Plan
     */
    public function createPlan(Product $product, int $amount, string $interval): Plan
    {
        $stripePlan = $this->createStripePlan($product->getStripeProduct(), $amount, $interval);
        return Plan::create([
            'product_id' => $product->id,
            'stripe_id' => $stripePlan->id,
            'amount' => $amount,
            'interval' => $interval
        ]);
    }

    /**
     * @param StripeProduct $product
     * @param int $amount
     * @param string $interval
     * @return StripePlan
     */
    public function createStripePlan(StripeProduct $product, int $amount, string $interval): StripePlan
    {
        return StripePlan::create([
            'amount' => $amount,
            'interval' => $interval,
            'product' => $product->id,
            'currency' => self::CURRENCY
        ]);
    }

    /**
     * @param string $name
     * @return Product
     */
    public function createProduct(string $name): Product
    {
        $stripeProduct = $this->createStripeProduct($name);

        return Product::create([
            'stripe_id' => $stripeProduct->id,
            'name' => $name,

        ]);
    }

    /**
     * @param string $name
     * @param int $storage
     * @return StripeProduct
     */
    public function createStripeProduct (string $name, int $storage = 0): StripeProduct
    {
        return StripeProduct::create([
            'name' => $name,
            'type' => 'service',
            'metadata' => [
                'storage' => $storage
            ]
        ]);
    }

    /**
     * @param Product $product
     * @return FTPQuotaLimits
     */
    public function createQuotaLimits (Product $product): FTPQuotaLimits
    {
        $ftpGroup = new FTPGroup([
            'groupname' => str_replace_array(' ', [''], $product->name).'_'.$product->storage,
            'members' => ''
        ]);
        $ftpGroup->save();

        $product->ftpGroup()->associate($ftpGroup);
        $product->save();

        $ftpQuotaLimits = new FTPQuotaLimits([
            'name' => $ftpGroup->groupname,
            'quota_type' => FTPQuotaLimits::GROUP_QUOTA_TYPE,
            'limit_type' => FTPQuotaLimits::HARD_TYPE,
            'bytes_out_avail' => $product->storage,
        ]);
        $ftpQuotaLimits->save();

        return $ftpQuotaLimits;
    }

    /**
     * @param array $params
     *
     * @return Plan|bool
     */
    public function create(array $params)
    {
        $planStripe = \Stripe\Plan::create(array(
            'amount' => $this->convertPrice($params['price']),
            'interval' => $params['interval'],
            'product' => [
                'name' => $params['title'],
            ],
            'currency' => self::CURRENCY,
        ));
        if (\strlen($planStripe->id) > 0) {
            $this->product = new Plan();
            $this->product->price = (float)$params['price'];
            $this->product->title = $params['title'];
            $this->product->stripe_id = $planStripe->id;
            $this->product->description = $params['description'];
            $this->product->interval = $params['interval'];
            $this->product->currency = self::CURRENCY;
            $this->product->storage = $params['storage'];
            $this->product->plan_for_upgrade_id = $params['plan_for_upgrade_id'] ?? null;
            $this->product->save();

            $ftpGroup = new FTPGroup([
                'groupname' => str_replace_array(' ', [''], $this->product->title),
                'members' => ''
            ]);
            $ftpGroup->save();

            $this->product->ftpGroup()->associate($ftpGroup);
            $this->product->save();

            $ftpQuotaLimits = new FTPQuotaLimits([
                'name' => $ftpGroup->groupname,
                'quota_type' => FTPQuotaLimits::GROUP_QUOTA_TYPE,
                'limit_type' => FTPQuotaLimits::HARD_TYPE,
                'bytes_out_avail' => $this->product->storage * 1000000000,
            ]);
            $ftpQuotaLimits->save();

            return $this->product;
        }

        return false;
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function get($id)
    {
        return $this->product->find($id);
    }

    /**
     * @param $id
     *
     * @throws \Exception
     * @return bool|null
     */
    public function delete($id): ?bool
    {
        $plan = $this->product->find($id);
        $stripe_id = $plan->stripe_id;
        if (\strlen($stripe_id) > 0) {
            $planStripe = \Stripe\Plan::retrieve($stripe_id);
            $product_id = $planStripe->product;
            $planStripe->delete();

            if (\strlen($product_id) > 0) {
                $product = Product::retrieve($product_id);
                $product->delete();
            }

        }
        return $plan->delete();
    }

    /**
     * @param $id
     * @param $params
     * @return mixed|static
     */
    public function update($id, $params)
    {
        $plan = $this->product->find($id);

        if (\strlen($plan->stripe_id) > 0) {
            if (
                $plan->interval !== $params['interval'] ||
                ($plan->price / 100) !== $params['price']
            ) {
                $planStripe = \Stripe\Plan::create(array(
                    'amount' => $this->convertPrice($params['price']),
                    'interval' => $params['interval'],
                    'product' => array(
                        'name' => $params['title'],
                    ),
                    'currency' => self::CURRENCY,
                ));
                $plan->stripe_id = $planStripe->id;
            }
        } else {

            $planStripe = \Stripe\Plan::create(array(
                'amount' => $this->convertPrice($params['price']),
                'interval' => $params['interval'],
                'product' => array(
                    'name' => $params['title'],
                ),
                'currency' => self::CURRENCY,
            ));
            $plan->stripe_id = $planStripe->id;
        }
        $plan->price = (float)$params['price'];
        $plan->title = $params['title'];
        $plan->description = $params['description'];
        $plan->interval = $params['interval'];
        $plan->storage = $params['storage'];
        $plan->plan_for_upgrade_id = $params['plan_for_upgrade_id'] ?? null;
        $plan->save();

        return $plan;
    }
}