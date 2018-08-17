<?php

namespace App\Transformers\Media;

use App\Classes\DateClass;
use LukeVear\LaravelTransformer\AbstractTransformer;

class LicenseTransformer extends AbstractTransformer
{
    /**
     * @param \App\Models\License $model
     * @return array
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
            'url' => route('license', $model->bill_file)
        ];
    }
}