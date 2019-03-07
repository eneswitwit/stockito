<?php

// namespace
namespace App\Transformers;

// use
use App\Classes\DateClass;
use App\Support\Database\CacheQueryBuilder;
use Carbon\Carbon;
use LukeVear\LaravelTransformer\AbstractTransformer;
use LukeVear\LaravelTransformer\TransformerEngine;

/**
 * Class UserTransformer
 *
 * @package App\Transformers
 */
class UserTransformer extends AbstractTransformer
{

    use CacheQueryBuilder;

    /**
     * @param \App\Models\User $model
     *
     * @return array
     * @throws \Exception
     */
    public function run($model): array
    {
        $subscription = $model->subscriptions()->first();
        return [
            'id' => $model->id,
            'email' => $model->email,
            'creative' => $model->creative ? new TransformerEngine($model->creative, new CreativeTransformer()) : null,
            'brand' => $model->brand ? new TransformerEngine($model->brand, new BrandTransformer()) : null,
            'subscribed' => $model->subscribed('main'),
            'onGracePeriod' => $model->subscription('main') ? $model->subscription('main')->onGracePeriod() : false,
            'subscription' => $subscription ? new TransformerEngine($subscription,
                new SubscriptionTransformer()) : null,
            'hasSubscriptions' => (bool)$model->subscriptions()->count(),
            'cardBrand' => $model->card_brand,
            'cardLastFour' => $model->card_last_four,
            'trialEndsAtCard' => $model->trial_ends_at ? Carbon::parse($model->trial_ends_at)->format('m/y') : ''
        ];
    }
}