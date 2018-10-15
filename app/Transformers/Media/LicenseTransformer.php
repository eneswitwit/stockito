<?php

// namespace
namespace App\Transformers\Media;

// use
use App\Classes\DateClass;
use App\Transformers\UsageLicenseTransformer;
use LukeVear\LaravelTransformer\AbstractTransformer;
use LukeVear\LaravelTransformer\TransformerEngine;

/**
 * Class LicenseTransformer
 *
 * @package App\Transformers\Media
 */
class LicenseTransformer extends AbstractTransformer
{
    /**
     * @param \App\Models\License $model
     *
     * @return array
     * @throws \Exception
     */
    public function run($model) : array
    {
        return [
            'id' => $model->id,
            'license_type' => $model->license_type,
            'type' => $model->getLicenseTitle(),
            'color' => $model->getLicenseColor(),
            'usage' => $model->usage,
            'printrun' => $model->printrun,
            'createdAt' => $model->created_at,
            'expiredAt' => $model->expired_at ? DateClass::transformCarbon($model->expired_at) : null,
            'startAt' => $model->start_at ? DateClass::transformCarbon($model->start_at) : null,
            'invoiceNumber' => $model->invoice_number,
            'invoiceNumberBy' => $model->invoice_number_by,
            'territory' => $model->territory,
            'billFile' => $model->bill_file,
            'billFileOriginName' => $model->bill_file_origin_name,
            'url' => route('license', $model->bill_file),
            'usageLicenses' => new TransformerEngine($model->usageLicenses, new UsageLicenseTransformer())
        ];
    }
}