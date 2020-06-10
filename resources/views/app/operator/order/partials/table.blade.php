<div class="table-responsive">
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>CUSTOMER</th>
                <th>DATE</th>
                <th>EMAIL</th>
                <th>STATUS</th>
                <th>PAYMENT STATUS</th>
                <th>AMOUNT</th>
                <th>DUE DATE</th>
                <th>CONTROLS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $index => $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->tenant->name }}</td>
                    <td>{{ $order->created_at->format('d M Y') }}</td>
                    <td>{{ $order->email }}</td>
                    <td>{!! $order->status() !!}</td>
                    <th>{!! $order->payment_status() !!}</th>
                    <td>{{ $order->currency ." ". number_format($order->charged, 2) }}</td>
                    <td>{{ $order->expiry_date->format('d M Y')  }}</td>
                    <th><a href="{{ route('operator.customer.billing.order.details', [$order->tenant_id, $order->id]) }}" class="btn btn-default"> DETAILS </a></th>
                    
                </tr>
            @empty
                <tr>
                    <td colspan="5"> No Order Found In Database. </td>
                </tr>
            @endforelse
        </tbody>    
     
    </table>
    {!! $orders->appends(request()->query())->links() !!}


</div>