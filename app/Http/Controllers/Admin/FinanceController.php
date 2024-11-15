<?php

// namespace
namespace App\Http\Controllers\Admin;

// use
use AdminSection;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Http\Response;
use Yajra\Datatables\Datatables;
use Illuminate\Http\JsonResponse;


/**
 * Class CreativeController
 *
 * @package App\Http\Controllers\Admin
 */
class FinanceController extends Controller
{
    /**
     * @var InvoiceService
     */
    protected $invoiceService;

    /**
     * FinanceController constructor.
     *
     * @param InvoiceService $invoiceService
     */
    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return AdminSection::view(view('admin.finance.index'), 'Finance & Invoices');
    }

    /**
     * @return JsonResponse
     * @throws \Exception
     */
    public function FinanceData(): JsonResponse
    {
        return Datatables::of(new Invoice())->make(true);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $invoice = Invoice::findOrFail($id);
        $stripeInvoice = $invoice->getStripeObject();
        return AdminSection::view(view('admin.invoices.show', ['invoice' => $stripeInvoice, 'in' => $invoice]),
            'Invoice #' . $invoice->number);
    }

    /**
     * @param $id
     *
     * @return Response
     * @throws \Throwable
     */
    public function download($id)
    {
        $invoice = Invoice::findOrFail($id);
        $html = $this->invoiceService->getView($invoice);
        $snappy = \App::make('snappy.pdf');
        return new Response(
            $snappy->getOutputFromHtml($html),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $invoice->getFileName() . '"'
            )
        );
    }
}