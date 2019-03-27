<?php

// namespace
namespace App\Transformers;

// use
use App\Support\Database\CacheQueryBuilder;
use LukeVear\LaravelTransformer\AbstractTransformer;
use LukeVear\LaravelTransformer\TransformerEngine;
use App\Classes\DateClass;

/**
 * Class LicenseTransformer
 *
 * @package App\Transformers
 */
class MediaLicenseTransformer extends AbstractTransformer
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
            'type' => $model->getLicenseTitle(),
            'license_type' => $model->license_type,
            'usageLicenses' => new TransformerEngine($model->usageLicenses, new UsageLicenseTransformer()),
            'printrun' => $model->printrun,
            'usage' => $model->usage,
            'territory' => $model->territory,
            'anyLimitations' => $model->any_limitations,
            'invoiceNumber' => $model->invoice_number,
            'invoiceNumberBy' => $model->invoice_number_by,
            'createdAt' => $model->created_at,
            'expiredAt' => $model->expired_at ? DateClass::transformCarbon($model->expired_at) : '',
            'startAt' => $model->start_at ? DateClass::transformCarbon($model->start_at) : '',
            'billFile' => $model->bill_file,
            'billFileOriginName' => $model->bill_file_origin_name,
        ];
    }
}