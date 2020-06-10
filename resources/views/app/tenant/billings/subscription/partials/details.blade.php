<div role="tabpanel2">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs nav-theme" role="tablist">
        <li role="presentation" class="active"><a href="#tab10" aria-controls="home" role="tab" data-toggle="tab"> Subscription Details </a></li>

        <li role="presentation"><a href="#tab11" aria-controls="profile" role="tab" data-toggle="tab">Orders </a></li>

        <li role="presentation"><a href="#tab12" aria-controls="profile" role="tab" data-toggle="tab">Transaction </a></li>

        
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">

        <div role="tabpanel2" class="tab-pane fade in active" id="tab10">

            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th>PERIOD</th>
                        <td>{{ $subscription->start_time->format('Y M') }}</td>
                    </tr>
                    <tr>
                        <th>START DATE</th>
                        <td>{{ $subscription->start_time->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>EXPIRATION DATE</th>
                        <td>{{ $subscription->end_time->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>CUSTOMER</th>
                        <td> <a href="{{ route('operator.customer.show', [$subscription->tenant_id]) }}">{{ $subscription->tenant->name }} </a></td>
                    </tr>
                    <tr>
                        <th>BILLING METHOD</th>
                        <td>{{ strtoupper($subscription->billing_method) }}</td>
                    </tr>
                    <tr>
                        <th>DURATION</th>
                        <td>{{ $subscription->duration .' DAYS' }}</td>
                    </tr>
                    <tr>
                        <th>PILOT LINE</th>
                        <td>{{ $subscription->pilot_line }}</td>
                    </tr>
                    <tr>
                        <th>PLAN</th>
                        <td>{{ strtoupper($subscription->package->name) }}</td>
                    </tr>
                    <tr>
                        <th>DESCRIPTION</th>
                        <td>{{ $subscription->description }}</td>
                    </tr>
                    <tr>
                        <th>AMOUNT</th>
                        <td>{{ $subscription->currency ." ". number_format($subscription->total, 2) }}</td>
                    </tr>
                    <tr>
                        <th>EXTRA MSISDN</th>
                        <td>{!! $subscription->extra_msisdn !!}</td>
                    </tr>
                    <tr>
                        <th>STATUS</th>
                        <td>{!! $subscription->status() !!}</td>
                    </tr>
                    <tr>
                        <th>PAYMENT STATUS</th>
                        <td>{!! $subscription->payment_status() !!}</td>
                    </tr>
                    <tr>
                        <th>ADDONS</th>
                        <td>{!! $subscription->addons !!}</td>
                    </tr>
                    <tr>
                        <th>ACCOUNT MANAGER</th>
                        <td>{{ $subscription->manager ? $subscription->manager->name : '' }}</td>
                    </tr>
                </table>
            </div>
                
        </div>
        <div role="tabpanel2" class="tab-pane fade" id="tab11">
            <div class="">
                <h4>Customer Orders </h4>
            </div>
            <div class="table-responsive">
                
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>TYPE</th>
                            <th>DATE</th>
                            <th>BILLING METHOD</th>
                            <th>STATUS</th>
                            <th>PAYMENT STATUS</th>
                            <th>AMOUNT</th>
                            <th>DUE DATE</th>
                            <th>OPERATOR</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subscription->billings as $index => $billing)
                            <tr>
                                <td>{{ $billing->id }}</td>
                                <td>{{ strtoupper($billing->billing_type) }}</td>
                                <td>{{ $billing->created_at->format('d M Y') }}</td>
                                <td>{{ strtoupper($billing->billing_method) }}</td>
                                <td>{!! $billing->status() !!}</td>
                                <th>{!! $billing->payment_status() !!}</th>
                                <td>{{ $billing->currency . number_format($billing->charged, 2) }}</td>
                                <td>{{ $billing->due_date }}</td>
                                <td>{{ $billing->issuer_name  }}</td>
                                <th><a href="{{ route('tenant.billing.order.show', [$tenant->domain, $billing->id]) }}" class="btn btn-success btn-xs"> DETAILS </a></th>
                                
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"> No Order Found In Database. </td>
                            </tr>
                        @endforelse
                    </tbody>    
                 
                </table>

            </div>
        </div>

        <div role="tabpanel2" class="tab-pane fade" id="tab12">
            
            <div class="">
                <h4>Customer Transactions</h4>
                
            </div>
            <div class="table-responsive">
                
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>TRANSACTION NO.</th>
                            <th>PAYMENT METHOD</th>
                            <th>AMOUNT</th>
                            <th>STATUS</th>
                            <th>DATE</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subscription->payments as $index => $payment)
                            <tr>
                                <td>{{ $index+1 }}</td>
                                <td>{{ $payment->transaction_no }}</td>
                                <td>{{ strtoupper($payment->payment_method) }}</td>
                                <td>{{ $payment->currency . number_format($payment->amount, 2) }}</td>
                                <td class="h4">{!! $payment->status() !!}</td>
                                <td>{{ $payment->created_at->format('d M Y') }}</td>
                                <th><a href="{{ route('tenant.billing.transaction.show', [$tenant->domain, $payment->id]) }}" class="btn btn-success btn-xs"> <i class="fa fa-eye-slash"></i> DETAILS </a></th>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7"> No Transaction Found In Database. </td>
                            </tr>
                        @endforelse
                    </tbody>    
                 
                </table>

            </div>
        </div>

    </div>
</div>