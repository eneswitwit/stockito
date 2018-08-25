<?php

namespace App\Transformers;

use App\Models\Plan;
use Illuminate\Support\Arr;
use LukeVear\LaravelTransformer\AbstractTransformer;
use Stripe\Product as StripeProduct;

class PlanTransformer extends AbstractTransformer
{
    /**
     * @param Plan $model
     * @return array
     */
    public function run($model): array
    {
        $plan = $model->getStripePlan();
        $product = StripeProduct::retrieve($plan->product);

        return [
            'id' => $model->id,
            'plan' => $plan,
            'title' => $product->name,
            'period' => Arr::get($model::getIntervalTitles(), $plan->interval, null),
            'amount' => $plan->amount,
            'price' => $plan->amount,
            'currencySymbol' => $model->getCurrencySymbol()
        ];
    }
}