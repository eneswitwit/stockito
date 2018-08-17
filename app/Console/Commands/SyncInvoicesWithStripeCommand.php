<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Console\Command;
use Stripe\Invoice as StripeInvoice;
use Stripe\Stripe;

class SyncInvoicesWithStripeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:invoices {--f|force : Full Refresh with clearing DB}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync invoices with Stripe';

    /**
     * @var InvoiceService
     */
    protected $invoiceService;

    /**
     * SyncInvoicesWithStripeCommand constructor.
     * @param InvoiceService $invoiceService
     */
    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        (new Invoice)->whereNotNull('id')->delete();

        $lastInvoiceId = null;
        do {
            $this->info('Requesting the invoices');
            $stripeInvoices = StripeInvoice::all(['limit' => 100, 'starting_after' => $lastInvoiceId]);
            $stripeInvoicesCount = count($stripeInvoices->data);
            foreach ($stripeInvoices->data as $key => $stripeInvoice) {
                $this->info('Invoice '.($key+1).' of '.$stripeInvoicesCount. ' '.$stripeInvoice->id);

                $this->invoiceService->createInvoice([
                    'number' => $stripeInvoice->number,
                    'id' => $stripeInvoice->id,
                    'total' => $stripeInvoice->total,
                    'currency' => $stripeInvoice->currency,
                    'customer' => $stripeInvoice->customer,
                    'date' => $stripeInvoice->date,
                    'paid' => $stripeInvoice->paid
                ]);

                $lastInvoiceId = $stripeInvoice->id;
            }

        } while ($stripeInvoices->has_more);

    }
}
