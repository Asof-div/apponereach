<div class="table-responsive">
    <table class="table table-condensed table-striped">
        <tr>
            <th>Type</th>
            <td>{{ strtoupper($payment->transaction_type) }}</td>
            <th>Payment Method</th>
            <td>{{ strtoupper($payment->payment_method) }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{!! $payment->status() !!}</td>
            <th>Date </th>
            <th>{{ $payment->created_at->format('M d, Y, h:i A')  }}</th>
        </tr>

    </table>
</div>
<div class="table-responsive">

    @foreach($payment->billings as $order)
        <div class="h4">
            Order Summary : {{ $order->id }} <a href="{{ route('tenant.billing.order.show', [$tenant->domain, $order->id]) }}" class="btn btn-success btn-xs"> <i class="fa fa-eye"></i> view </a>
        </div>
        <table class="table table-condensed table-striped">
           
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Product</th>
                    <th>Item</th>
                    <th>Duration</th>
                    <th>Amount</th>
                    <th>Charged</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>

                @foreach(json_decode($order->ordered_items, true) as $index => $item)

                    <tr>
                        <td>{{ $index+1 }}</td>
                        <td>
                            <span class="display-box h4"> {{ isset($item['product']) ? $item['product'] : ''  }} </span>
                            <span>
                                {{ isset($item['description']) ? $item['description'] : ''  }}    
                            </span>
                        </td>
                        <td>{{ $item['items'] }}</td>
                        <td>{{ isset($item['period']) ? $item['period'] : ''  }}</td>
                        <td>{{ isset($item['amount']) ? number_format($item['amount'], 2) : ''  }}</td>
                        <td>{{ isset($item['charged']) ? number_format($item['charged'], 2) : ''  }}</td>
               
                        <td>
                            @if( isset($item['status']) & $item['status'] == 1 )

                                <span class="h3 text-success">&#10003;</span>
                            @else
                                
                                <span class="h3 text-danger">&#10005;</span>

                            @endif

                        </td>
                    
                    </tr>

                @endforeach
                <tr>
                    <th colspan="4"> <span class="h4"> Subtotal </span></th>
                    <th>{{ number_format($order->amount, 2) }}</th>
                    <th>{{ number_format($order->charged, 2) }}</th>
                    <th>
                        @if( strtolower($order->status) == 'success' )

                            <span class="label f-s-18 text-success">&#10003;</span>
                        @elseif(strtolower($order->status) == 'processing')
                        
                            <span class="label f-s-18">&#10042;</span>

                        @else
                            
                            <span class="label f-s-18 text-danger">&#10005;</span>

                        @endif
                    </th>
                </tr>
                
            </tbody>
            
        </table>
    @endforeach

    <hr class="horizonal-line-thick">
    <table class="table table-condensed table-striped">
        <tfoot>
            <tr class="h4">
                <th>Total &nbsp; {!! $payment->status() !!}</th>
                <th class="text-right">{{ $payment->currency.number_format($payment->amount, 2) }}</th>
            </tr>
        </tfoot>
    </table>

</div>
