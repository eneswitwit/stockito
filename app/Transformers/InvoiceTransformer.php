<?php

// namespace
namespace App\Transformers;

// use
use App\Models\Invoice;
use LukeVear\LaravelTransformer\AbstractTransformer;

/**
 * Class InvoiceTransformer
 *
 * @package App\Transformers
 */
class InvoiceTransformer extends AbstractTransformer
{
    /**
     * @param Invoice $model
     * @return array
     */
    public function run($model): array
    {
        return [
            'number' => $model->id,
            'amount' => $model->amount,
            'date' => $model->date,
            'paid' => $model->paid,
            'brand_id' => $model->brand_id,
            'invoiceFileName' => $model->getFileName(),
            'downloadPdfUrl' => route('download.invoice.pdf', $model)
        ];
    }
}