<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Invoice</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Customer Invoice">
        <meta name="author" content="5marks">

        <link rel="stylesheet" href="{{ asset('admin-invoice/css/bootstrap.css') }}">
        <style>
            .pdf-root {
                background-image: url('{{ asset('images/PDF-background.jpg') }}');
                background-repeat: no-repeat;
                width: 760px;
                height: 1074px;
                margin: 25px auto;
            }
            .header {
                width: 100%;
                height: 103px;
            }
            .title {
                width: calc(100% - (88px + 58px));
                padding-left: 88px;
                padding-right: 58px;
                padding-top: 70px;
            }
            .stokito-address {
                font-size: 10px;
            }
            .title-blocks {
                margin-top: 25px;
                height: 100px;
            }
            .title-block {
                display: inline-table;
                height: 100%;
            }
            .left-block {
                width: 167px;
            }
            .right-block {
                width: 243px;
                margin-left: 200px;
                line-height: 15px;
            }
            .invoice-address {
                font-size: 13px;
                margin-top: 2px;
                margin-left: 5px;
                margin-right: 5px;
                line-height: 15px;
            }
            .invoice-data-title {
                display: inline-block;
                width: 41%;
                text-align: right;
            }
            .invoice-data-value {
                display: inline-block;
                width: 55%;
                margin-left: 5px;
            }
        </style>
    </head>
    <body>
        <div class="pdf-root">
            <div class="header">
                header
            </div>
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
                            <div class="invoice-data-value">xxxxxxxxxx</div>
                        </div>
                        <div class="invoice-data-row">
                            <div class="invoice-data-title">Invoice ID</div>
                            <div class="invoice-data-value">xxxxxxxxxx</div>
                        </div>
                        <div class="invoice-data-row">
                            <div class="invoice-data-title">Client ID</div>
                            <div class="invoice-data-value">xxxxxxxxxx</div>
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
                data
            </div>
        </div>
    </body>
</html>
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

-->