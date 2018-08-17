<?php

namespace App\Http\Controllers\Webhooks;

use App\Services\InvoiceService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Voucher;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Response;

class StripeController extends Controller
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function index(Request $request) : JsonResponse
    {
        $data = $request->all();
        if ($data['type'] === 'coupon.created')
        {
            $this->create($data['data']['object']);
        } elseif ($data['type'] === 'coupon.deleted'){
            $this->delete($data['data']['object']);
        } elseif ($data['type']=== 'invoice.created'){
            $this->InvoiceCreated($data['data']['object']);
        }
        return new JsonResponse ($data['type']);
    }

    /**
     * @param $data
     */
    private function create($data)
    {
        $voucher = Voucher::where('code','=', $data['id'])->first();

        if(!$voucher) {
            $voucher = new Voucher();
            $voucher->sale = $data['percent_off'];
            $voucher->code = $data['id'];
            $voucher->duration = $data['duration'];
            $voucher->amount_off = $data['amount_off'];
            $voucher->currency = $data['currency'];
            $voucher->duration_in_months = $data['duration_in_months'];
            $voucher->max_redemptions =  $data['max_redemptions'];
            $voucher->percent_off =  $data['percent_off'];
            $voucher->redeem_by =  $data['redeem_by'];
            $voucher->save();
        }
    }

    /**
     * @param $data
     */
    private function  delete($data)
    {
        $voucher = Voucher::where('code','=', $data['id'])->first();
        if ($voucher) {
            $voucher->delete();
        }
    }

    /**
     * @param $data
     */
    private function InvoiceCreated($data)
    {
        $number = $data['number'];
        $invoice = Invoice::where('number', $number)->first();
        if (!$invoice) {
            $invoiceStripe =  new Invoice();
            $invoiceStripe->number = $number;
            $invoiceStripe->stripe_id = $data['id'];
            $invoiceStripe->amount = $data['lines']['data'][0]['amount'];
            $invoiceStripe->currency = $data['currency'];
            $invoiceStripe->customer = $data['customer'];
           // $invoiceStripe->customer_email = ;
            $invoiceStripe->date = Carbon::createFromTimeStamp($data['date']);
            $invoiceStripe->description = $data['lines']['data'][0]['description'];
            //$invoiceStripe->invoice ;
            $invoiceStripe->livemode = $data['livemode'];
            $invoiceStripe->period_start = Carbon::createFromTimeStamp($data['period_start']);
            $invoiceStripe->period_end = Carbon::createFromTimeStamp($data['period_end']);
           // $invoiceStripe->plan;
            $invoiceStripe->proration =  $data['lines']['data'][0]['proration'];
            $invoiceStripe->quantity = $data['lines']['data'][0]['quantity'];
            $invoiceStripe->subscription = $data['lines']['data'][0]['subscription_item'];
           // $invoiceStripe->unit_amount;
            
            $invoiceStripe->save();
        }
    }

    /**
     * @param $data
     * @return Response
     */
    public function handleInvoiceCreated($data): Response
    {
        $invoiceService = new InvoiceService();
        $invoice = (new Invoice())->where('number', $data['number'])->first();

        if (!$invoice) {
            $invoiceService->createInvoice($data);
        }

        return new Response();
    }
}




