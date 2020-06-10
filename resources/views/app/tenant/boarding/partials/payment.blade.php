
	<div class="panel panel-default m-t-50">
		<div class="panel-heading">
			<h4 class="panel-title">
				<span class="h4"> Billing Summary  <i class="fa fa-shopping-cart"></i></span>
			</h4>
		</div>
		<div class="panel-body clearfix">
			<div class="f-s-15 p-l-15">
                <div> 
                	<span>Transaction  No.: </span>
                    <span> {{ $payment->transaction_no }} </span>
                </div>
                <div> 
                	<span>Description: </span>
                    <span> {{ $payment->description }} </span>
                </div>
                <div>
                	<span>Amount: </span>
                    <span> {{ $payment->currency}} {{ number_format($payment->amount, 2) }} </span>
                </div>
                <div>
                	<span>Email: </span>
                    <span> {{ $payment->email}} </span>
                </div>
            </div>
			<div class="table-responsive">
				<table class="table">
					<thead>
						<tr>
							<th>Order Item</th>
							<th>Description</th>
							<th>Price</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
						@php 
							$total_amount = 0;
						@endphp
						@foreach($payment->billings as $billing)

							<tr>
								<td>{{ ucfirst($billing->billing_type) }}</td>
								<td>{{ $billing->description }}</td>
								<td>{{ $billing->currency. number_format($billing->charged, 2) }}</td>
								<td>{!! $billing->status() !!}</td>
							</tr>	
							@php 
								$total_amount = $total_amount + $billing->charged;
							@endphp
						@endforeach
					</tbody>
					<tfoot>
						<tr>
							<th colspan="2">Subtotal</th>
							<th>{{ $payment->currency}}<span class="total-amount">{{ number_format($payment->amount, 2) }} </span> </th>
							<th>{!! $payment->status() !!}</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
		<div class="panel panel-footer">
			<div class="clearfix">
			    <button type="button" class="btn btn-success btn-lg btn-block pay-now" > <i class="fa fa-plus-circle fa-lg"></i> Pay Now!</button>
                                    
			</div>
		</div>
	</div>