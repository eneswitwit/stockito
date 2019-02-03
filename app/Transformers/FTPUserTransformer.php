<?php

// namespace
namespace App\Transformers;

// use
use LukeVear\LaravelTransformer\AbstractTransformer;

/**
 * Class FTPUserTransformer
 *
 * @package App\Transformers
 */
class FTPUserTransformer extends AbstractTransformer
{
    /**
     * @param \App\Models\User $model
     *
     * @return array
     * @throws \Exception
     */
    public function run($model): array
    {
        return $model->toArray();
    }
}