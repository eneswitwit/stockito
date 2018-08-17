<?php

namespace App\Transformers;

use App\Transformers\Activity\BrandTransformer;
use LukeVear\LaravelTransformer\AbstractTransformer;
use LukeVear\LaravelTransformer\TransformerEngine;

class ActivityTransformer extends AbstractTransformer
{
    /**
     * @param \App\Models\Activity $model
     * @return array
     * @throws \Exception
     */
    public function run($model) : array
    {
        return [
            'id' => $model->id,
            'type' => $model->type,
            'brand' => new TransformerEngine($model->brand, new BrandTransformer()),
            'text' => $model->message,
            'createdAt' => $model->created_at
        ];
    }
}