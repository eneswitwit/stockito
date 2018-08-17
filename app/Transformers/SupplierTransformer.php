<?php

namespace App\Transformers;

use App\Models\Supplier;
use LukeVear\LaravelTransformer\AbstractTransformer;

class SupplierTransformer extends AbstractTransformer
{

    /**
     * @param Supplier $model
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