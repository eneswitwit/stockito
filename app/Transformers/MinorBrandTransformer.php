<?php

namespace App\Transformers;

use LukeVear\LaravelTransformer\AbstractTransformer;
use LukeVear\LaravelTransformer\TransformerEngine;
use Log;

class MinorBrandTransformer extends AbstractTransformer
{
    /**
     * @param \App\Models\Brand $model
     *
     * @return array
     * @throws \Exception
     */
    public function run($model) : array
    {
        return [
            'id' => $model->id,
            'company_name' => $model->company_name,
            'logo' => $model->getLogoUrl(),
            'creatives' => $model->creatives ? new TransformerEngine($model->creatives, new CreativeTransformer()) : null,
        ];
    }
}