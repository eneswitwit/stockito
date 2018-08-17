<?php

namespace App\Transformers;

use LukeVear\LaravelTransformer\AbstractTransformer;
use LukeVear\LaravelTransformer\TransformerEngine;

class BrandTransformer extends AbstractTransformer
{
    /**
     * @param \App\Models\Brand $model
     *
     * @return array
     * @throws \Exception
     */
    public function run($model) : array
    {
        return [
            'id' => $model->id,
            'address_1' => $model->address_1,
            'address_2' => $model->address_2,
            'brand_name' => $model->brand_name,
            'city' => $model->city,
            'company_name' => $model->company_name,
            'contact' => [
                'first_name' => $model->contact_first_name,
                'last_name' => $model->contact_last_name,
                'title' => $model->contact_title,
            ],
            'ftp' => [
                'host' => env('HOST').':2222',
                'user' => $model->ftpUser ? $model->ftpUser->userid : '',
                'password' => $model->ftpUser ? $model->ftpUser->passwd : '',
            ],
            'country_id' => $model->country_id,
            'country' => $model->country,
            'eur_uid' => $model->eur_uid,
            'homepage' => $model->homepage,
            'phone' => $model->phone,
            'zip' => $model->zip,
            'plan' => $model->plan ? new TransformerEngine($model->plan, new PlanTransformer()) : null,
            'logo' => $model->getLogoUrl()
        ];
    }
}