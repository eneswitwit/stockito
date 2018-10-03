<?php

// namespace
namespace App\Services;

// use
use App\Models\Invoice;
use App\Models\User;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Contracts\View\View;

/**
 * Class InvoiceService
 *
 * @package App\Services
 */
class InvoiceService
{

    /**
     * @param $data
     *
     * @return Invoice
     */
    public function createInvoice(array $data): Invoice
    {
        $invoice = new Invoice();
        $invoice->number = $data['number'];
        $invoice->stripe_id = $data['id'];
        $invoice->amount = $data['total'];
        $invoice->currency = $data['currency'];
        $invoice->customer = $data['customer'];
        $invoice->date = Carbon::createFromTimeStamp($data['date']);
        $invoice->paid = $data['paid'];

        $invoice->save();

        if ($invoice->user && $invoice->user->brand) {
            $invoice->brand()->associate($invoice->user->brand);
            $invoice->save();
        }

        return $invoice;
    }

    /**
     * @param Invoice $invoice
     *
     * @return View
     */
    public function getView(Invoice $invoice): View
    {
        $brand = User::where('stripe_id', $invoice->customer)->first()->brand;
        $country = $brand->country;
        return view('pdf.invoice', [
            'brand' => $brand,
            'country' => $country,
            'invoice' => $invoice,
            'stripeInvoice' => $invoice->getStripeObject()
        ]);
    }

    /**
     * @param Invoice $invoice
     *
     * @return PDF
     * @throws \Throwable
     */
    public function getPdf(Invoice $invoice): PDF
    {
        $view = $this->getView($invoice)->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);

        return $pdf;
    }

}