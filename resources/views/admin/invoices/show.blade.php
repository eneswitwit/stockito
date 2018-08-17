<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Invoice <span class="label label-{{ $invoice->paid ? 'success' : 'danger' }}">{{ $invoice->paid ? 'Paid' : 'Not Paid' }}</span></h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <th>Date:</th>
                                    <td>{{ \Carbon\Carbon::createFromTimestamp($invoice->date) }}</td>
                                </tr>
                                <tr>
                                    <th>Products:</th>
                                    <td>
                                        <table class="table">
                                            @foreach($invoice->lines->data as $line)
                                                @php($product = $line->plan)
                                                <tr>
                                                    <td>{{ $product->object }} ({{ $product->id }})</td>
                                                    <td>{{ $product->amount / 100 }} {{ $product->currency }}</td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Subtotal:</th>
                                    <th>{{ $invoice->subtotal / 100 .' '.$invoice->currency }}</th>
                                </tr>
                                @if($invoice->discount)
                                    @php($coupon = $invoice->discount->coupon)
                                    <tr>
                                        <th>Discount:</th>
                                        <th>
                                            @if($coupon->amount_off)
                                                -{{ ($invoice->subtotal - $coupon->amount_off) / 100 .' '. $invoice->currency }}
                                            @elseif($coupon->percent_off)
                                                -{{ ($invoice->subtotal * $coupon->percent_off/100) / 100 .' '. $invoice->currency }}
                                            @endif
                                        </th>
                                    </tr>
                                @endif
                                <tr>
                                    <th>Total:</th>
                                    <th>{{ $invoice->total / 100 .' '.$invoice->currency }}</th>
                                </tr>
                                </tbody>
                            </table>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4>Status</h4>
                                </div>
                                <div class="panel-body">
                                    <p><b>Paid:</b> {{ $invoice->paid ? 'Yes' : 'Not Paid' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <a href="{{ route('admin.invoices.download', $in) }}" class="btn btn-primary">Download</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
