<?php

// namespace
namespace App\Transformers;

// use
use App\Models\User;
use App\Support\Database\CacheQueryBuilder;
use LukeVear\LaravelTransformer\AbstractTransformer;


/**
 * Class CreativeBrandsTransformer
 *
 * @package App\Transformers
 */
class CreativeBrandsTransformer extends AbstractTransformer
{

    use CacheQueryBuilder;

    /**
     * @param User $model
     * @return array
     */
    public function run($model) : array
    {
        return $model->creative->brands()->with('user', 'creatives')->get()->toArray();
    }

}