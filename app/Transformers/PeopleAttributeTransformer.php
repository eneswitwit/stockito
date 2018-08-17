<?php

namespace App\Transformers;

use App\Models\PeopleAttribute;
use LukeVear\LaravelTransformer\AbstractTransformer;

class PeopleAttributeTransformer extends AbstractTransformer
{

    /**
     * @param PeopleAttribute $model
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