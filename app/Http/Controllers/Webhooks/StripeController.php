<?php

// namespace
namespace App\Http\Controllers\Webhooks;

// use
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Voucher;
use App\Models\Invoice;
use Carbon\Carbon;

/**
 * Class StripeController
 * @package App\Http\Controllers\Webhooks
 */
class StripeController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function index(Request $request): JsonResponse
    {
        $data = $request->all();

        if ($data['type'] === 'coupon.created') {
            $this->createCoupon($data['data']['object']);
        } elseif ($data['type'] === 'coupon.deleted') {
            $this->deleteCoupon($data['data']['object']);
        } elseif ($data['type'] === 'invoice.created') {
            $this->invoiceCreated($data['data']['object']);
        } elseif ($data['type'] === 'invoice.payment_succeeded') {
            $this->invoicePaid($data['data']['object']);
        }

        return new JsonResponse ($data['type']);
    }

    /**
     * @param $data
     */
    private function createCoupon($data)
    {
        $voucher = Voucher::where('code', '=', $data['id'])->first();

        if (!$voucher) {
            $voucher = new Voucher();
            $voucher->sale = $data['percent_off'];
            $voucher->code = $data['id'];
            $voucher->duration = $data['duration'];
            $voucher->amount_off = $data['amount_off'];
            $voucher->currency = $data['currency'];
            $voucher->duration_in_months = $data['duration_in_months'];
            $voucher->max_redemptions = $data['max_redemptions'];
            $voucher->percent_off = $data['percent_off'];
            $voucher->redeem_by = $data['redeem_by'];
            $voucher->save();
        }
    }

    /**
     * @param $data
     *
     * @throws \Exception
     */
    private function deleteCoupon($data)
    {
        $voucher = Voucher::where('code', '=', $data['id'])->first();

        if ($voucher) {
            $voucher->delete();
        }
    }

    /**
     * @param $data
     */
    private function invoiceCreated($data)
    {
        $number = $data['number'];
        $invoice = Invoice::where('number', $number)->first();

        if (!$invoice) {
            $invoiceStripe = new Invoice();
            $invoiceStripe->number = $number;
            $invoiceStripe->stripe_id = $data['id'];
            $invoiceStripe->amount = $data['lines']['data'][0]['amount'];
            $invoiceStripe->currency = $data['currency'];
            $invoiceStripe->customer = $data['customer'];
            $invoiceStripe->date = Carbon::createFromTimeStamp($data['date']);
            $invoiceStripe->description = $data['lines']['data'][0]['description'];
            $invoiceStripe->livemode = $data['livemode'];
            $invoiceStripe->period_start = Carbon::createFromTimeStamp($data['period_start']);
            $invoiceStripe->period_end = Carbon::createFromTimeStamp($data['period_end']);
            $invoiceStripe->proration = $data['lines']['data'][0]['proration'];
            $invoiceStripe->paid = $data['paid'];
            $invoiceStripe->quantity = $data['lines']['data'][0]['quantity'];
            $invoiceStripe->subscription = $data['lines']['data'][0]['subscription_item'];
            $invoiceStripe->tax = $data['tax'];
            $invoiceStripe->tax_percent = $data['tax_percent'];
            $invoiceStripe->save();

            if ($invoiceStripe->user && $invoiceStripe->user->brand) {
                $invoiceStripe->brand()->associate($invoiceStripe->user->brand);
                $invoiceStripe->save();
            }
        }

    }

    /**
     * @param $data
     */
    private function invoicePaid($data)
    {
        $invoice = Invoice::where('stripe_id', $data['id'])->first();

        if ($invoice) {
            $invoice->paid = 1;
            $invoice->save();
        }

    }

}
