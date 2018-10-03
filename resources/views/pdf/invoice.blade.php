<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Customer Invoice">
    <meta name="author" content="5marks">
    <link rel="stylesheet" href="{{ public_path('admin-invoice/css/pdf.css') }}">
</head>
<body>
<table class="main-table">
    <tr>
        <td class="stokito-address-td">Stockito GmbH, Address, Austria 777</td>
    </tr>
    <tr>
        <td class="title-blocks-td">
            <table class="first-table">
                <tr>
                    <td class="title-block-td left-block">
                        <div class="invoice-address"> Invoice Address </div>
                        <div class="invoice-address">
                            <p>
                                {{ $brand->name }}
                            </p>
                            <p>
                                {{ $brand->address_1 }}
                            </p>
                            <p>
                                {{ $brand->zip }}, {{ $brand->city }}
                            </p>
                            <p>
                                {{ $country->name }}
                            </p>
                        </div>
                    </td>
                    <td></td>
                    <td class="title-block-td right-block">
                        <div class="invoice-data-row">
                            <div class="invoice-data-title">Invoice Date</div>
                            <div class="invoice-data-value">{{ date('M j, Y', $stripeInvoice->date) }}</div>
                        </div>
                        <div class="invoice-data-row">
                            <div class="invoice-data-title">Invoice ID</div>
                            <div class="invoice-data-value">{{ $invoice->id }}</div>
                        </div>
                        <div class="invoice-data-row">
                            <div class="invoice-data-title">Client ID</div>
                            <div class="invoice-data-value">{{ $brand->id }}</div>
                        </div>
                        <div class="invoice-data-row">
                            <div class="invoice-data-title">Client VAT ID</div>
                            <div class="invoice-data-value"> {{ $brand->eur_uid }}</div>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="data">
            <table class="first-table listing">
                <tr>
                    <td class="invoice-data-row-td">
                        <div class="data-title">Period</div>
                        <div class="data-value">Date/Period of
                            time: {{ date('M j, Y', $stripeInvoice->period_start) .' - ' . date('M j, Y', $stripeInvoice->period_end) }}</div>
                    </td>
                </tr>
                <tr>
                    <td class="invoice-data-row-td table-titles">
                        <div class="table-title data-title">Description</div>
                        <div class="table-title data-title">Unit price</div>
                        <div class="table-title data-title">Quantity</div>
                        <div class="table-title data-title">Amount</div>
                    </td>
                </tr>
                <tr>
                    <td class="invoice-data-row-td table-titles-line">
                        <div class="line"></div>
                    </td>
                </tr>
                @foreach($stripeInvoice->lines->data as $subscription)
                    @php($amount = $subscription->amount / 100)
                    @php($amountPlan = $subscription->plan->amount / 100)
                    @php($total = ($subscription->plan->amount * $subscription->quantity) / 100)
                    <tr>
                        <td class="invoice-data-row-td table-values">
                            <div class="table-value data-value">{{ $subscription->description }}</div>
                            <div class="table-value data-value">{{ number_format($amountPlan, 2) }} {{ $stripeInvoice->currency }}</div>
                            <div class="table-value data-value">{{ $subscription->quantity }}</div>
                            <div class="table-value data-value">{{ number_format($amount, 2) }} {{ $stripeInvoice->currency }}</div>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td class="invoice-data-row-td table-titles-line">
                        <div class="line"></div>
                    </td>
                </tr>
                <tr>
                    <td class="invoice-data-row-td table-totals right-block first">
                        <div class="table-total data-total-title">Subtotal</div>
                        <div class="table-total data-total-value">{{ number_format(($stripeInvoice->subtotal / 100), 2) }} {{ $stripeInvoice->currency }}</div>
                    </td>
                </tr>
                <tr>
                    <td class="invoice-data-row-td table-totals right-block">
                        <div class="table-total data-total-title">VAT</div>
                        <div class="table-total data-total-value"> 0 {{ $stripeInvoice->currency }}</div>
                    </td>
                </tr>
                <tr>
                    <td class="invoice-data-row-td table-totals right-block">
                        <div class="table-total data-total-title">Total</div>
                        <div class="table-total data-total-value">{{ number_format(($stripeInvoice->total / 100), 2) }} {{ $stripeInvoice->currency }}</div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="data-terms invoice-data-row">Terms of Payment</td>
    </tr>
    <tr>
        <td class="data-note invoice-data-row">Note</td>
    </tr>
    <tr>
        <td class="data-details invoice-data-row">Stockito GmbH / Contact details / Further Info / Bank details</td>
    </tr>
    <tr>
        <td class="clean"></td>
    </tr>
</table>
</body>
</html>