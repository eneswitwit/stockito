<?php

// namespace
namespace App\Transformers;

// use
use App\Models\Plan;
use App\Support\Database\CacheQueryBuilder;
use LukeVear\LaravelTransformer\AbstractTransformer;
use LukeVear\LaravelTransformer\TransformerEngine;

/**
 * Class SubscriptionTransformer
 *
 * @package App\Transformers
 */
class SubscriptionTransformer extends AbstractTransformer
{

    use CacheQueryBuilder;

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return array
     * @throws \Exception
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
            'downgrade_to_stripe_plan' => $model->downgrade_to_stripe_plan,
            'onTrial' => $model->onTrial(),
            'active' => $model->active(),
            'plan' => $plan ? new TransformerEngine($plan, new PlanTransformer()) : null,
            'autoCharge' => !$model->ends_at,
            'product' => $product ? new TransformerEngine($product, new ProductTransformer()) : null
        ];
    }
}