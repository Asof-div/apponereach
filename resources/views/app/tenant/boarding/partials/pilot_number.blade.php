
<div class="col-md-7 p-0 p-r-5">

	<div class="panel panel-default m-t-50">
		<div class="panel-heading">
			<h4 class="panel-title">
				<span class="h4"> Choose A Number For You Business </span>
			</h4>
		</div>
		<div class="panel-body">
			
			<div class="table-responsive">

				<ul class="nav nav-tabs nav-theme" role="tablist">

                    <li role="presentation" class="active"><a href="#regular_number_tab" aria-controls="regular_number_tab" role="tab" data-toggle="tab">Regular Number &#x20A6;20,000.00/Per Year </a></li>
                    
                    <li role="presentation"><a href="#vanity_number_tab" aria-controls="vanity_number_tab" role="tab" data-toggle="tab">Vanity Number &#x20A6;30,000.00/Per Year </a></li>
  
                </ul>

                <div class="tab-content" style="padding:15px; ">

                    <div role="tabpanel" class="tab-pane active fadein" id="regular_number_tab">
                        <div class="col-md-12 clearfix">
                        	<span class="text-danger f-s-14"> Note input must be in a correct number format.</span>
                        </div>
                        <div class="form-group clearfix">
                        	<div class="col-md-8">
                        		<input type="text" name="regular_search" value="" class="form-control regular-number" placeholder="0700221XXXX">
                        	</div>
                        	<div class="col-md-4">
                        		<button class="btn regular-search"> <i class="fa fa-search"></i> Search </button>
                        	</div>
                        </div>

                    </div>

                    <div role="tabpanel" class="tab-pane fadein" id="vanity_number_tab">
                    	<div class="col-md-12 clearfix">
                        	<span class="text-danger f-s-14"> Note input must be in a correct number format.</span>
                        </div>
                        <div class="form-group">
                        	<div class="col-md-8">
                        		<input type="text" name="vanity_search" value="" class="form-control" placeholder="0700221ONER">
                        	</div>
                        	<div class="col-md-4">
                        		<button class="btn"> <i class="fa fa-search"></i> Search </button>
                        	</div>
                        </div>
                    </div>
                </div>
			</div>


		    <div class="grid-container" style="height: 700px; width: 100%;">
				<div class="droppable" >
		            <div class="list" >
		                <div class="heading">
		                    <h4 class="list-title bg-primary">AVAILABLE NUMBERS</h4>
		                </div>
		                <div id="regular_search_content" class="cards" ondragover="allowDrop(event)" ondrop="drop(event)">
		                                        
		                </div>
		            </div>
		        </div>

		        <div class="droppable" >
		            <div class="list" >
		                <div class="cards">
		                     <i class="fa fa-arrow-right fa-2x"></i>                   
		                </div>
		            </div>
		        </div>

		        <div class="droppable" >
		            <div class="list" >
		                <div class="heading">
		                    <h4 class="list-title bg-primary">PURCHASE NUMBERS <span class="update-badge pull-right"><i class="fa fa-cart-plus"></i> <span class="badge"> {{ $pilot_numbers->count() }} </span> </span></h4>
		                </div>
		                <div class="cards pilot-number-container" ondragover="allowDrop(event)" ondrop="drop(event)">
		                	@php 
								$did_fee = 0;
							@endphp
		                	@foreach($pilot_numbers as $index => $pilot)
		                        <div class="list-card" >
		                            <p class="phone-route"> {{ $pilot->number}} </p>
		                            <div class="card-footer">
		                                <span class="type"><i class="fa fa-file"></i> {{ $pilot->type }}</span>
		                                <span class="price"><i class="fa fa-money"></i> &#x20A6;{{ $pilot->amount }} </span>
		                                <span class="countdown"><i class="fa fa-clock-o"></i> 
		                                	 <span class='release-countdown' data-id="{{ $pilot->id }}" data-time="{{ $pilot->countdown }}"></span>
		                                </span>
		                                <span class="cart"> 
		                                	<button type='button' data-pilot_number-id="{{ $pilot->id }}" class='btn btn-xs btn-danger remove-reserved'>  &times; Remove </button>
		                                </span>

		                            </div>
		                        </div>
		                        @php 
									$did_fee = $did_fee + $pilot->amount;
								@endphp
		                    @endforeach                      
		                </div>
		                <div class="heading">
		                    <h4 class="list-title bg-success p-10 clearfix"> DID FEE: <span class="pull-right"> &#x20A6;<span class="did-fee">{{ number_format($did_fee, 2) }} </span> </span></h4>
		                </div>
		            </div>
		        </div>
			</div>
		
		</div>
		<div class="panel panel-footer">
			<div class="clearfix">
				<form action="{{ route('tenant.registration.save_transaction', [$tenant->domain]) }}" method="post">
					{{ csrf_field() }}
					<input type="hidden" name="tenant_id" value="{{ $tenant->id }}">
					<input type="hidden" name="subscription_id" value="{{ $subscription->id }}">
					<button class="btn btn-primary"> <i class="fa fa-forward"></i> Skip Number Selection </button>
					<button type="submit" class="btn btn-success pull-right"> <i class="fa fa-save"></i> Save Order </button>
				</form>
			</div>
		</div>
	</div>
</div>


<div class="col-md-5 p-0 p-l-5">

	<div class="panel panel-default m-t-50">
		<div class="panel-heading">
			<h4 class="panel-title">
				<span class="h4"> Billing Summary  <i class="fa fa-shopping-cart"></i></span>
			</h4>
		</div>
		<div class="panel-body clearfix">
			
			<div class="table-responsive">
				<table class="table">
					<thead>
						<tr>
							<th>Order Type</th>
							<th>Description</th>
							<th>Price</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
						@php 
							$total_amount = 0;
						@endphp
						@foreach($subscription->billings as $billing)

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
						<tr>
							<td> DID Feee </td>
							<td> Pilot Number Purchase</td>
							<td>{{ $billing->currency }} <span class="did-fee">{{ number_format($did_fee, 2) }} </span></td>
							<td></td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<th colspan="2">Subtotal</th>
							<th colspan="2">{{ $subscription->currency}}<span class="total-amount">{{ number_format($total_amount + $did_fee, 2) }} </span> </th>
						</tr>
					</tfoot>
				</table>
			</div>
		
		</div>
		<div class="panel panel-footer">
			<div class="clearfix">
				<button class="btn btn-primary"> <i class="fa fa-forward"></i> Skip Number Selection </button>
			</div>
		</div>
	</div>
</div>


