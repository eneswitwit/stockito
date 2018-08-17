<?php

namespace App\Transformers;

use LukeVear\LaravelTransformer\AbstractTransformer;

class CreativeTransformer extends AbstractTransformer
{
    /**
     * @param \App\Models\Creative $model
     * @return array
     */
    public function run($model) : array
    {
        return $model->toArray();
    }
}