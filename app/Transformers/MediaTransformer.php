<?php

// namespace
namespace App\Transformers;

// use
use App\Managers\MediaManager;
use App\Support\Database\CacheQueryBuilder;
use LukeVear\LaravelTransformer\AbstractTransformer;
use LukeVear\LaravelTransformer\TransformerEngine;
use App\Transformers\Media\LicenseTransformer;

/**
 * Class MediaTransformer
 *
 * @package App\Transformers
 */
class MediaTransformer extends AbstractTransformer
{

    use CacheQueryBuilder;

    /**
     * @param \App\Models\Media $model
     *
     * @return array
     * @throws \Exception
     */
    public function run($model): array
    {
        return [
            'id' => $model->id,
            'license' => $model->license ? new TransformerEngine($model->license, new LicenseTransformer()) : null,
            'content_type' => $model->content_type,
            'thumbnail' => MediaManager::getThumbnailUrl($model),
        ];
    }
}