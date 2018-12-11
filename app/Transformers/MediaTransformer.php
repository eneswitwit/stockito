<?php

// namespace
namespace App\Transformers;

// use
use App\Managers\MediaManager;
use App\Services\UploadService;
use App\Transformers\Media\LicenseTransformer;
use LukeVear\LaravelTransformer\AbstractTransformer;
use LukeVear\LaravelTransformer\TransformerEngine;

/**
 * Class MediaTransformer
 *
 * @package App\Transformers
 */
class MediaTransformer extends AbstractTransformer
{
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
            'title' => $model->title,
            'license' => $model->license ? new TransformerEngine($model->license, new LicenseTransformer()) : null,
            'created_by' => $model->createdBy,
            'file_name' => $model->file_name,
            'content_type' => $model->content_type,
            'origin_name' => $model->origin_name,
            'peoples_attribute' => $model->peoples_attribute,
            'thumbnail' => MediaManager::getThumbnailUrl($model),
            'url' => MediaManager::getMediaUrl($model),
            'type' => $model->getType(),
            'source' => $model->source,
            'keywords' => $model->keywords,
            'fileType' => $model->getTypeTitle(),
            'category' => $model->category,
            'iptc' => $model->getIPTC()?$model->getIPTC()->all():null,
            'exif' => $model->getAllEXIF(),
            'downloadLink' => url('/api/medias/' . $model->id . '/download'),
            'imageInfo' => [
                'width' => $model->width,
                'height' => $model->height,
                'fileSize' => UploadService::getUnitsOfBytes($model->file_size)
            ],
            'uploadedAt' => $model->created_at->format('M d. Y'),
            'notes' => $model->notes
        ];
    }
}