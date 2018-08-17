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
        .invoice-head td {
            padding: 0 8px;
        }
        .container {
            padding-top:30px;
        }
        .invoice-body{
            background-color:transparent;
        }
        .invoice-thank{
            margin-top: 60px;
            padding: 5px;
        }
        address{
            margin-top:15px;
        }
    </style>
</head>

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
</html>
