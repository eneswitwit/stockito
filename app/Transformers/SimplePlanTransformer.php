<?php

// namespace
namespace App\Transformers;

// use
use App\Services\UploadService;
use Illuminate\Support\Arr;
use LukeVear\LaravelTransformer\AbstractTransformer;

/**
 * Class SimplePlanTransformer
 * @package App\Transformers
 */
class SimplePlanTransformer extends AbstractTransformer
{
    /**
     * @param \App\Models\Plan $model
     * @return array
     */
    public function run($model): array
    {
        return [
            'id' => $model->id,
            'title' => $model->product->name,
            'stripe_id' => $model->stripe_id,
            'period' => Arr::get($model::getIntervalTitles(), $model->interval, null),
            'price' => $model->price,
            'storage' => $model->product->storage,
            'storageGb' => UploadService::getUnitsOfBytes($model->product->storage),
            'currencySymbol' => $model->getCurrencySymbol()
        ];
    }
}