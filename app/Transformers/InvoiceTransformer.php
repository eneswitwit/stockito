<?php

namespace App\Transformers;

use App\Models\Invoice;
use LukeVear\LaravelTransformer\AbstractTransformer;

class InvoiceTransformer extends AbstractTransformer
{
    /**
     * @param Invoice $model
     * @return array
     */
    public function run($model): array
    {
        return [
            'number' => $model->number,
            'amount' => $model->amount,
            'date' => $model->date,
            'paid' => $model->paid,
            'brand_id' => $model->brand_id,
            'invoiceFileName' => $model->getFileName(),
            'downloadPdfUrl' => route('download.invoice.pdf', $model)
        ];
    }
}