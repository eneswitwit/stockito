<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Invoice</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Customer Invoice">
        <meta name="author" content="5marks">
        <link rel="stylesheet" href="{{ public_path('admin-invoice/css/bootstrap.css') }}">
        <link rel="stylesheet" href="{{ public_path('admin-invoice/css/pdf.css') }}">
    </head>
    <body>
        <table class="pdf-root">
            <tr class="header">
                <td></td>
            </tr>
            <tr>
                <td class="title">
                    <table>
                        <tr>
                            <td class="stokito-address">Stockito GmbH, Address, Austria</td>
                        </tr>
                        <tr>
                            <td class="title-blocks">

                                <div class="title-block left-block">
                                    <div class="invoice-address">Invoice Address</div>
                                    <div class="invoice-address">some long address some long address some long address some long address some long address </div>
                                </div>
                                <div class="title-block right-block">
                                    <div class="invoice-data-row">
                                        <div class="invoice-data-title">Invoice Date</div>
                                        <div class="invoice-data-value">{{ date('M j, Y', $stripeInvoice->date) }}</div>
                                    </div>
                                    <div class="invoice-data-row">
                                        <div class="invoice-data-title">Invoice ID</div>
                                        <div class="invoice-data-value">{{ $stripeInvoice->id }}</div>
                                    </div>
                                    <div class="invoice-data-row">
                                        <div class="invoice-data-title">Client ID</div>
                                        <div class="invoice-data-value">{{ $stripeInvoice->customer }}</div>
                                    </div>
                                    <div class="invoice-data-row">
                                        <div class="invoice-data-title">License Holder</div>
                                        <div class="invoice-data-value">xxxxxxxxxx</div>
                                    </div>
                                    <div class="invoice-data-row">
                                        <div class="invoice-data-title">Client VAT ID</div>
                                        <div class="invoice-data-value">xxxxxxxxxx</div>
                                    </div>
                                    <div class="invoice-data-row">
                                        <div class="invoice-data-title">Page</div>
                                        <div class="invoice-data-value">x/x</div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

        </table>
    </body>
</html>
        <!-- <div class="pdf-root">
            <div class="header"></div>
            <div class="title">
                <div class="stokito-address">Stockito GmbH, Address, Austria</div>
                <div class="title-blocks">
                    <div class="title-block left-block">
                        <div class="invoice-address">Invoice Address</div>
                        <div class="invoice-address">some long address some long address some long address some long address some long address </div>
                    </div>
                    <div class="title-block right-block">
                        <div class="invoice-data-row">
                            <div class="invoice-data-title">Invoice Date</div>
                            <div class="invoice-data-value">{{ date('M j, Y', $stripeInvoice->date) }}</div>
                        </div>
                        <div class="invoice-data-row">
                            <div class="invoice-data-title">Invoice ID</div>
                            <div class="invoice-data-value">{{ $stripeInvoice->id }}</div>
                        </div>
                        <div class="invoice-data-row">
                            <div class="invoice-data-title">Client ID</div>
                            <div class="invoice-data-value">{{ $stripeInvoice->customer }}</div>
                        </div>
                        <div class="invoice-data-row">
                            <div class="invoice-data-title">License Holder</div>
                            <div class="invoice-data-value">xxxxxxxxxx</div>
                        </div>
                        <div class="invoice-data-row">
                            <div class="invoice-data-title">Client VAT ID</div>
                            <div class="invoice-data-value">xxxxxxxxxx</div>
                        </div>
                        <div class="invoice-data-row">
                            <div class="invoice-data-title">Page</div>
                            <div class="invoice-data-value">x/x</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="data">
                <div class="invoice-data-row">
                    <div class="data-title">Period:</div>
                    <div class="data-value">Date/Period of time: {{ date('M j, Y', $stripeInvoice->period_start) .' - ' . date('M j, Y', $stripeInvoice->period_end) }}</div>
                </div>
                <div class="invoice-data-row table-titles">
                    <div class="table-title data-title">Description</div>
                    <div class="table-title data-title">Unit price</div>
                    <div class="table-title data-title">Quantity</div>
                    <div class="table-title data-title">Amount</div>
                </div>
                @foreach($stripeInvoice->lines->data as $subscription)
                    @if ($subscription->type === 'subscription')
                        @php($amount = $subscription->amount / 100)
                        <div class="invoice-data-row table-values">
                            <div class="table-value data-value">{{ $subscription->plan->name }} ($ {{number_format($subscription->plan->amount / 100,2) }}/{{ $subscription->plan->interval }})</div>
                            <div class="table-value data-value"></div>
                            <div class="table-value data-value"></div>
                            <div class="table-value data-value"></div>
                        </div>
                    @endif
                @endforeach
                <div class="invoice-data-row table-totals">
                    <div class="table-total data-total-title">Subtotal</div>
                    <div class="table-total data-total-value">xx,xx EUR</div>
                </div>
                <div class="invoice-data-row table-totals">
                    <div class="table-total data-total-title">TVA</div>
                    <div class="table-total data-total-value">xx,xx EUR</div>
                </div>
                <div class="invoice-data-row table-totals">
                    <div class="table-total data-total-title">Total</div>
                    <div class="table-total data-total-value">{{ number_format(($stripeInvoice->total / 100), 2) }} EUR</div>
                </div>
                <div class="invoice-data-row terms">
                    Terms of Payment
                </div>
                <div class="invoice-data-row note">
                    Note
                </div>
                <div class="invoice-data-row details">
                    Stockito GmbH / Contact details / Further Info / Bank details
                </div>
            </div>
        </div> -->
<!--
<body>
<div class="container">
    <div class="row">
        {{--<div class="span4">--}}
            {{--<address>--}}
                {{--<strong>5marks, LLC.</strong><br>--}}
                {{--P.O Box 3171<br>--}}
                {{--Kent, WA 98089<br>--}}
            {{--</address>--}}
        {{--</div>--}}
        <div class="span4 well">
            <table class="invoice-head">
                <tbody>
                <tr>
                    <td class="pull-right"><strong>Customer #</strong></td>
                    <td>{{ $stripeInvoice->customer }}</td>
                </tr>
                <tr>
                    <td class="pull-right"><strong>Invoice #</strong></td>
                    <td>{{ $stripeInvoice->id }}</td>
                </tr>
                <tr>
                    <td class="pull-right"><strong>Date</strong></td>
                    <td>{{ date('M j, Y', $stripeInvoice->date) }}</td>
                </tr>
                <tr>
                    <td class="pull-right"><strong>Period</strong></td>
                    <td>{{ date('M j, Y', $stripeInvoice->period_start) .' to ' . date('M j, Y', $stripeInvoice->period_end) }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="span8">
            <h2>Invoice</h2>
        </div>
    </div>
    <div class="row">
        <div class="span8 well invoice-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Amount</th>
                </tr>
                </thead>
                <tbody>

                @php($total = 0)
                @foreach($stripeInvoice->lines->data as $subscription)
                    @if ($subscription->type === 'subscription')
                        @php($amount = $subscription->amount / 100)
                        <tr>
                            <td>{{ $subscription->plan->name }} ($ {{number_format($subscription->plan->amount / 100,2) }}/{{ $subscription->plan->interval }})</td>
                            <td>{{ date('M j, Y', $subscription->period->start) .' - ' . date('M j, Y', $subscription->period->end) }}</td>
                            <td>${{ number_format($amount,2) }}</td>
                        </tr>
                        @php($total += $amount)
                    @endif
                @endforeach

                @if(isset($stripeInvoice->discount))
                    <tr>
                        <td>{{ $stripeInvoice->discount->coupon->id.' ('.$stripeInvoice->discount->coupon->percent_off }}% off)</td>
                        <td>&nbsp;</td>
                        <td>-${{ number_format($total * ($stripeInvoice->discount->coupon->percent_off/100),2) }}</td>
                    </tr>
                @endif
                <tr>
                    <td>&nbsp;</td>
                    <td><strong>Total</strong></td>
                    <td><strong>${{ number_format(($stripeInvoice->total / 100), 2) }}</strong></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="{{ asset('admin-invoice/js/bootstrap.min.js') }}"></script>
</body>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="{{ public_path('admin-invoice/js/bootstrap.min.js') }}"></script>
-->
