<div class="col-md-12 p-0">

	<ul class="nav nav-tabs nav-theme">
		<li class="active"><a href="#nav-plan" data-toggle="tab"> Subscription Details </a></li>
		<li><a href="#nav-payment" data-toggle="tab"> Orders </a></li>

	</ul>
	<div class="tab-content">
		<div class="tab-pane fade active in" id="nav-plan">
		    <h3 class="m-t-10"> {{ $subscription->start_time->format('F Y') }} </h3>
			<div class="table-responsive">
				<table class="table table-striped">
					<tbody class="f-s-16">
	                    <tr>
	                        <th>STATUS</th>
	                        <td>{!! $subscription->status() !!}</td>
	                    </tr>
						<tr>
							<th> NUMBER LIMIT </th>
							<td> {{ $subscription->package ? $subscription->package->msisdn_limit : '3' }} </td>
						</tr>
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
	                        <td>{{ $subscription->tenant->name }}</td>
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
	                        <td>{{ $subscription->currency.number_format($subscription->total, 2) }}</td>
	                    </tr>
	                    <tr>
	                        <th>EXTRA MSISDN</th>
	                        <td>{!! $subscription->extra_msisdn !!}</td>
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
						
					</tbody>
				</table>
			</div>
		</div>
		<div class="tab-pane fade" id="nav-payment">
		    <div class="table-responsive">
			    <h4> PAYMENT HISTORY </h4>

			    <table class="table table-striped">
			        <thead>
                        <tr>
                            <th>ID</th>
                            <th>CUSTOMER</th>
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
                                <td>{{ $billing->tenant->name }}</td>
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
	</div>
</div>