<div class="table-responsive">
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>TRANSACTION NO</th>
                <th>PAYMENT METHOD</th>
                <th>BILL</th>
                <th>STATUS</th>
                <th>DATE</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $index => $payment)
                <tr>
                    <td>{{ $index+1 }}</td>
                    <td>{{ $payment->tenant->name }}</td>
                    <td>{{ $payment->transaction_no }}</td>
                    <td>{{ strtoupper($payment->payment_method) }}</td>
                    <td>{{ $payment->currency . number_format($payment->amount, 2) }}</td>
                    <td class="h4">{!! $payment->status() !!}</td>
                    <td>{{ $payment->created_at->format('d M Y') }}</td>
                    <th><a href="{{ route('operator.customer.transaction.show', [$payment->id]) }}" class="btn btn-success btn-xs"> <i class="fa fa-eye-slash"></i> DETAILS </a></th>
                    
                </tr>
            @empty
                <tr>
                    <td colspan="5"> No payment Found In Database. </td>
                </tr>
            @endforelse
        </tbody>    
     
    </table>

    {!! $payments->appends(request()->query())->links() !!}
</div>