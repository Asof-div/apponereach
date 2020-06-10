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
                        <td class="clearfix">{{ $subscription->start_time->format('Y M') }}
                            <a href="{{ route('operator.customer.order.create', [$subscription->tenant_id]) }}" class="btn btn-default pull-right"> <i class="fa fa-shopping-cart"></i> New Order </a> 

                        </td>
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
                        <td>{!! nl2br($subscription->description) !!}</td>
                    </tr>
                    <tr>
                        <th>AMOUNT</th>
                        <td>{{ $subscription->currency . number_format($subscription->total, 2) }}</td>
                    </tr>
                    <tr>
                        <th>EXTRA MSISDN</th>
                        @php 
                            $extra_msisdn = json_decode($subscription->extra_msisdn, true);
                        @endphp
                        @if( empty($extra_msisdn))
                        
                            <td> Non</td>
                        @else

                            <td>
                                <div>
                                    <span class="inline-element p-r-30 f-w-600">No.</span>
                                    <span class="inline-element">{{ isset($extra_msisdn['items']) ? $extra_msisdn['items'] : '0' }}</span>
                                </div>
                                <div>
                                    <span class="inline-element p-r-30 f-w-600">Amount</span>
                                    <span class="inline-element">{{ isset($extra_msisdn['price']) ? $subscription->currency.$extra_msisdn['price'] : $subscription->currency.'0.00' }}</span>
                                </div>                                            
                            </td>

                        @endif

                    </tr>
                    <tr>
                        <th>STATUS</th>
                        <td>{!! $subscription->status() !!}</td>
                    </tr>
                    <tr>
                        <th>PAYMENT STATUS</th>
                        <td>{!! $subscription->payment_status() !!}</td>
                    </tr>{{-- 
                    <tr>
                        <th>ADDONS</th>
                        <td>{!! $subscription->addons !!}</td>
                    </tr> --}}
                    <tr>
                        <th>ACCOUNT MANAGER</th>
                        <td>{{ $subscription->manager ? $subscription->manager->name : '' }}</td>
                    </tr>
                </table>
            </div>
                
        </div>
        <div role="tabpanel2" class="tab-pane fade" id="tab11">
            <div class="">
                <h4>Customer Orders <span class="pull-right">
                    <a href="{{ route('operator.customer.order.create', [$subscription->tenant_id]) }}" class="btn btn-default"> New Order </a> 
                    
                </span> </h4>
            </div>
            <div class="table-responsive">
                
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
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
                        @forelse($subscription->orders as $index => $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                                <td>{{ $order->email }}</td>
                                <td>{!! $order->status() !!}</td>
                                <th>{!! $order->payment_status() !!}</th>
                                <td>{{ $order->currency ." ". number_format($order->charged, 2) }}</td>
                                <td>{{ $order->expiry_date->format('d M Y')  }}</td>
                                <th><a href="{{ route('operator.customer.billing.order.details', [$order->tenant_id, $order->id]) }}" class="btn btn-default"> DETAILS </a></th>
                                
                            </tr>
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
                        @forelse($subscription->transactions as $index => $transaction)
                            <tr>
                                <td>{{ $index+1 }}</td>
                                <td>{{ $transaction->transaction_no }}</td>
                                <td>{{ strtoupper($transaction->payment_method) }}</td>
                                <td>{{ $transaction->currency . number_format($transaction->amount, 2) }}</td>
                                <td class="h4">{!! $transaction->status() !!}</td>
                                <td>{{ $transaction->created_at->format('d M Y') }}</td>
                                <th><a href="{{ route('operator.customer.transaction.show', [$transaction->id]) }}" class="btn btn-success btn-xs"> <i class="fa fa-eye-slash"></i> DETAILS </a></th>
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