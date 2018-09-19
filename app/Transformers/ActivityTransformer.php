<?php

// namespace
namespace App\Transformers;

// use
use App\Transformers\Activity\BrandTransformer;
use LukeVear\LaravelTransformer\AbstractTransformer;
use LukeVear\LaravelTransformer\TransformerEngine;

/**
 * Class ActivityTransformer
 *
 * @package App\Transformers
 */
class ActivityTransformer extends AbstractTransformer
{
    /**
     * @param \App\Models\Activity $model
     *
     * @return array
     * @throws \Exception
     */
    public function run($model): array
    {
        $parts = explode(':', $model->message);
        return [
            'id' => $model->id,
            'type' => $model->type,
            'brand' => new TransformerEngine($model->brand, new BrandTransformer()),
            'user' => $parts[0],
            'text' => $parts[1],
            'createdAt' => $model->created_at
        ];
    }
}