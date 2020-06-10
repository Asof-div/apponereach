<div class="table-responsive">
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>BILLING METHOD</th>
                <th>DURATION</th>
                <th>PLAN</th>
                <th>BILL</th>
                <th>PAYMENT STATUS</th>
                <th>STATUS</th>
                <th>START TIME</th>
                <th>END TIME</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($subscriptions as $index => $subscription)
                <tr>
                    <td>{{ $index+1 }}</td>
                    <td>{{ strtoupper($subscription->billing_method) }}</td>
                    <td>{{ $subscription->duration. ' Days'}}</td>
                    <td>{{ $subscription->package->name }}</td>
                    <td>{{ $subscription->currency . number_format($subscription->total, 2) }}</td>
                    <td>{!! $subscription->payment_status() !!}</td>
                    <td>{!! $subscription->status() !!}</td>
                    <td>{{ $subscription->start_time ? $subscription->start_time->format('Y M d') : '' }}</td>
                    <td>{{ $subscription->end_time ? $subscription->end_time->format('Y M d') : '' }}</td>
                    <th><a href="{{ route('tenant.billing.subscription.show', [$tenant->domain, $subscription->id]) }}" class="btn btn-success btn-xs"> <i class="fa fa-eye-slash"></i> DETAILS </a></th>
                    
                </tr>
            @empty
                <tr>
                    <td colspan="5"> No subscription Found In Database. </td>
                </tr>
            @endforelse
        </tbody>    
     
    </table>

    {!! $subscriptions->appends(request()->query())->links() !!}
</div>