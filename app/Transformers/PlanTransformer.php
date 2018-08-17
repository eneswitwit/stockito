<?php

namespace App\Transformers;

use App\Models\Plan;
use Illuminate\Support\Arr;
use LukeVear\LaravelTransformer\AbstractTransformer;

class PlanTransformer extends AbstractTransformer
{
    /**
     * @param Plan $model
     * @return array
     */
    public function run($model): array
    {
        $plan = $model->getStripePlan();

        return [
            'id' => $model->id,
            'plan' => $plan,
            'period' => Arr::get($model::getIntervalTitles(), $plan->interval, null),
            'amount' => $plan->amount,
            'price' => $plan->amount,
            'currencySymbol' => $model->getCurrencySymbol()
        ];
    }
}