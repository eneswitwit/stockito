<?php

namespace App\Transformers;

use App\Models\User;
use LukeVear\LaravelTransformer\AbstractTransformer;

class CreativeBrandsTransformer extends AbstractTransformer
{
    /**
     * @param User $model
     * @return array
     */
    public function run($model) : array
    {
        return $model->creative->brands()->with('user', 'creatives')->get()->toArray();
    }

}