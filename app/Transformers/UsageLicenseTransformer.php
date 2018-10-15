<?php

// namespace
namespace App\Transformers;

// use
use App\Classes\DateClass;
use LukeVear\LaravelTransformer\AbstractTransformer;
use LukeVear\LaravelTransformer\TransformerEngine;

/**
 * Class LicenseTransformer
 *
 * @package App\Transformers
 */
class UsageLicenseTransformer extends AbstractTransformer
{
    /**
     * @param \App\Models\UsageLicense $model
     *
     * @return array
     * @throws \Exception
     */
    public function run($model): array
    {
        return [
            'id' => $model->id,
            //'media' => $model->license ?($model->license->media ? new TransformerEngine($model->license->media, new MediaTransformer()) : '') : '',
            'type' => $model->license ? $model->license->getLicenseTitle() : '',
            'license_type' => $model->license ? $model->license->license_type : '',
            'printrun' => $model->printrun,
            'usage' => $model->usage,
            'territory' => $model->territory,
            'anyLimitations' => $model->any_limitations,
            'invoiceNumber' => $model->invoice_number,
            'invoiceNumberBy' => $model->invoice_number_by,
            'createdAt' => $model->created_at,
            'expiredAt' => $model->expired_at ? DateClass::transformCarbon($model->expired_at) : '',
            'startAt' => $model->start_at ? DateClass::transformCarbon($model->start_at) : '',
            //'createdBy' => $model->license->createdBy->getType()->name,
            'billFile' => $model->bill_file,
            'billFileOriginName' => $model->bill_file_origin_name,
            'url' => route('license', $model->bill_file)
            //'brandName' => $model->license->media->brand ? $model->license->media->brand->name : '',
            //'origin' => $model->license->media->supplier_id ? $model->license->media->supplier->name : '',
        ];
    }
}