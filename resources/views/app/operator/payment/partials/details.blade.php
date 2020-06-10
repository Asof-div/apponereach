


<div class="pl-5">
    <p class="h4"> 
        <span> Order No.: </span>
        <span> {{ $payment->order->id }} </span>
    </p>
        <p class="h4">
        <span>Payment Method: </span>
        <span> {{ $payment->payment_method  }}  </span>
    </p>
    <p class="h4">
        <span>Amount: </span>
        <span> {{ $payment->currency.number_format($payment->amount, 2)  }}  </span>
    </p>
    <p class="h4">
        <span>Email: </span>
        <span> {{ $payment->order->email }} </span>
    </p>
    <p class="h4">
        <span>Status: </span>
        <span> {!! $payment->order->status() !!}  </span>
    </p>
    <p class="h4">
        <span>Date: </span>
        <span> {{ $payment->order->ordered_date }} </span>
    </p>
</div>

<hr class="horizonal-line-thick">

<div class="table-responsive">
    <table class="table f-s-15">
        <thead>
        <tr>
            <th>Order Item</th>
            <th>Description</th>
            <th>Qty</th>
            <th class="text-right">Price</th>
        </tr>
        </thead>
        <tbody>

            @foreach($payment->order->items as $item)
                <tr >
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td class="text-right">{{ $item->charged  }}</td>
                </tr>   
            @endforeach    
            
        </tbody>
        <tfoot>
            @if($payment->order->discount > 0.0)
                <tr >
                    <td></td>
                    <td>Discount</td>
                    <td>{{ $payment->order->discount_type }}</td>
                    <td class="text-right"> {{ $payment->order->discount  }} </td>
                </tr>
            @endif
            <tr>
                <th></th>
                <th colspan="" >Total</th>
                <th colspan="2" class="text-right"> {{ $payment->order->charged  }}</th>
        </tr>
        </tfoot>
    </table>
</div>
