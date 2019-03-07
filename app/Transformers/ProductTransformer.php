<?php

namespace App\Transformers;

use App\Models\Product;
use App\Services\UploadService;
use App\Support\Database\CacheQueryBuilder;
use LukeVear\LaravelTransformer\AbstractTransformer;
use LukeVear\LaravelTransformer\TransformerEngine;

class ProductTransformer extends AbstractTransformer
{

    use CacheQueryBuilder;

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return array
     * @throws \Exception
     */
    public function run($model): array
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'storage' => $model->storage,
            'storageFormated' => UploadService::getUnitsOfBytes($model->storage),
            'plans' => new TransformerEngine($model->plans, new PlanTransformer())
        ];
    }
}