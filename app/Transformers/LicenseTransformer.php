<?php

// namespace
namespace App\Transformers;

// use
use App\Classes\DateClass;
use App\Support\Database\CacheQueryBuilder;
use LukeVear\LaravelTransformer\AbstractTransformer;
use LukeVear\LaravelTransformer\TransformerEngine;

/**
 * Class LicenseTransformer
 *
 * @package App\Transformers
 */
class LicenseTransformer extends AbstractTransformer
{
    use CacheQueryBuilder;

    /**
     * @param \App\Models\License $model
     * @return array
     * @throws \Exception
     */
    public function run($model) : array
    {
        return [
            'id' => $model->id,
            'media' => $model->media_id && $model->media ? new TransformerEngine($model->media, new MediaTransformer()) : '',
            'type' => $model->getLicenseTitle(),
            'license_type' => $model->license_type,
            'printrun' => $model->printrun,
            'usage' => $model->usage,
            'territory' => $model->territory,
            'anyLimitations' => $model->any_limitations,
            'invoiceNumber' => $model->invoice_number,
            'invoiceNumberBy' => $model->invoice_number_by,
            'createdAt' => $model->created_at,
            'expiredAt' => $model->expired_at ? DateClass::transformCarbon($model->expired_at) : '',
            'startAt' => $model->start_at ? DateClass::transformCarbon($model->start_at) : '',
            'createdBy' => $model->createdBy ? $model->createdBy->getType()->name : '',
            'billFile' => $model->bill_file,
            'billFileOriginName' => $model->bill_file_origin_name,
            'brandName' => $model->media->brand ? $model->media->brand->name : '',
            'origin' => $model->media->supplier_id ? $model->media->supplier->name : '',
            'usageLicenses' => new TransformerEngine($model->usageLicenses, new UsageLicenseTransformer())
        ];
    }
}