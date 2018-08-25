<?php

namespace App\Services;

use App\Models\Invoice;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Contracts\View\View;

class InvoiceService
{
//    /**
//     * @var View
//     */
//    protected $view;
//
//    /**
//     * InvoiceService constructor.
//     * @param View $view
//     */
//    public function __construct(View $view)
//    {
//        $this->view = $view;
//    }

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
        return view('pdf.invoice', ['invoice' => $invoice, 'stripeInvoice' => $invoice->getStripeObject()]);
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