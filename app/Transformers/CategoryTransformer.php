<?php

namespace App\Transformers;

use App\Models\Media\Category;
use LukeVear\LaravelTransformer\AbstractTransformer;

class CategoryTransformer extends AbstractTransformer
{

    /**
     * @param Category $model
     * @return array
     */
    public function run($model): array
    {
        return [
            'id' => $model->id,
            'name' => $model->name
        ];
    }
}