<?php

namespace App\Transformers;

use App\Models\Plan;
use LukeVear\LaravelTransformer\AbstractTransformer;
use LukeVear\LaravelTransformer\TransformerEngine;

class SubscriptionTransformer extends AbstractTransformer
{
    /**
     * @param \Laravel\Cashier\Subscription $model
     * @return array
     */
    public function run($model)
    {
        $plan = (new Plan())->where('stripe_id', 'LIKE', $model->stripe_plan)->first();
        $product = $plan ? $plan->product : null;
        return [
            'id' => $model->id,
            'created_at' => $model->created_at,
            'user_id' => $model->user_id,
            'name' => $model->name,
            'stripe_id' => $model->stripe_id,
            'trial_ends_at' => $model->trial_ends_at,
            'ends_at' => $model->ends_at,
            'onTrial' => $model->onTrial(),
            'active' => $model->active(),
            'plan' => $plan ? new TransformerEngine($plan, new PlanTransformer()) : null,
            'autoCharge' => !$model->ends_at,
            'product' => $product ? new TransformerEngine($product, new ProductTransformer()) : null
        ];
    }
}