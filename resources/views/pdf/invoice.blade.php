<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Stockito Invoice</title>
    <link rel="stylesheet" href="{{ public_path('admin-invoice/css/pdf.css') }}">
</head>

<body>

<div class="container">

    <table class="main-table">
        <tr>
            <td class="stokito-address-td"> Stockito GmbH | Bildagentur, Bildeinkauf & Recherchen | Kasernenstr. 17/7,
                4470 Enns, Austria | www.stockito.com
            </td>
        </tr>
        <tr>
            <td class="title-blocks-td">
                <table class="first-table">
                    <tr>
                        <td class="title-block-td left-block">
                            <div class="invoice-data-row">
                                <div class="invoice-row">Invoice Address</div>
                                <div class="invoice-data-value"></div>
                            </div>
                            <div class="invoice-data-row">
                                <div class="invoice-row"> {{ $brand->name }} </div>
                                <div class="invoice-data-value"></div>
                            </div>
                            <div class="invoice-data-row">
                                <div class="invoice-row"> {{ $brand->address_1 }} </div>
                                <div class="invoice-data-value"></div>
                            </div>
                            <div class="invoice-data-row">
                                <div class="invoice-row"> {{ $brand->zip }}, {{ $brand->city }} </div>
                                <div class="invoice-data-value"></div>
                            </div>
                            <div class="invoice-data-row">
                                <div class="invoice-row"> {{ $country->name }} </div>
                                <div class="invoice-data-value"></div>
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
                            <div class="data-title">Invoice ID {{ $invoice->id }}</div>
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
                                <div class="table-value data-value currency">{{ number_format($amountPlan, 2) }} {{ $stripeInvoice->currency }}</div>
                                <div class="table-value data-value">{{ $subscription->quantity }}</div>
                                <div class="table-value data-value currency">{{ number_format($amount, 2) }} {{ $stripeInvoice->currency }}</div>
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
            <td class="data-details invoice-data-row">
                <p> <b> Stockito GmbH | Bildagentur, Bildeinkauf & Recherchen | Kasernenstr. 17/7, 4470 Enns, Austria | www.stockito.com </b>  </p>
                <p>
                    Firmenbuchnummer : FN 311536 t, Landesgericht Steyr, Eingetragenes Einzelunternehmen, UID-Nr: ATU63946109
                </p>
                <p>
                    Bankverbindung : BA-CA, Konto Nr. 51512020794, BLZ 12000, IBAN : AT541200051512020794, BIC: BKAUATWW
                </p>
            </td>
        </tr>
        <tr>
            <td class="clean"></td>
        </tr>
    </table>
</div>
</body>
</html>