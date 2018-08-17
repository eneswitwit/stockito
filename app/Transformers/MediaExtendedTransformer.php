<?php

namespace App\Transformers;

use App\Managers\MediaManager;
use App\Services\UploadService;
use LukeVear\LaravelTransformer\AbstractTransformer;
use LukeVear\LaravelTransformer\TransformerEngine;

class MediaExtendedTransformer extends AbstractTransformer
{
    /**
     * @param \App\Models\Media $model
     * @return array
     * @throws
     */
    public function run($model) : array
    {
        return [
            'id' => $model->id,
            'title' => $model->title,
            'license' => $model->license ? new TransformerEngine($model->license, new \App\Transformers\Media\LicenseTransformer()) : null,
            'licenses' => $model->licenses ? new TransformerEngine($model->licenses, new \App\Transformers\Media\LicenseTransformer()) : null,
            'created_by' => $model->createdBy,
            'content_type' => $model->content_type,
            'file_name' => $model->file_name,
            'origin_name' => $model->origin_name,
            'url' => MediaManager::getMediaUrl($model),
            'thumbnail' => MediaManager::getThumbnailUrl($model),
            'keywords' => $model->keywords,
            'source' => $model->source,
            'supplier' => $model->supplier,
            'peopleAttributes' => $model->peopleAttributes,
            'category' => $model->category,
            'fileType' => $model->getTypeTitle(),
            'type' => $model->getType(),
            'iptc' => $model->getIPTC()->all(),
            'exif' => $model->getAllEXIF(),
            'downloadLink' => url('/api/medias/'.$model->id.'/download'),
            'playVideo' => \Storage::disk('brands')->url($model->file_name),
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