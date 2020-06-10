<div class="row bg-white">
	<div class="col-md-6">
		<div class="table-responsive">
			<table class="table table-condensed">
				<tbody>
					<tr>
						<td>Account Name</td>
						<td> 
							<i class="fa fa-building-o"></i> {{ $account->name }} 
							<a href="javascript:;" data-backdrop="static" data-toggle="modal" data-target=".edit-account-modal" class="btn btn-link pull-right btn-xs" ><i class="fa fa-edit"></i></a>
						</td>
					</tr>
					<tr>
						<td>Email</td>
						<td>{{$account->email}}</td>
					</tr>
					<tr>
						<td>Website</td>
						<td>{{$account->website}}</td>
					</tr>
					<tr>
						<td>Phone</td>
						<td>{{$account->phone}} </td>
					</tr>
					<tr>
						<td>Postal Code</td>
						<td>{{$account->postcode}}</td>
					</tr>
					<tr>
						<td>Address</td>
						<td>{!! nl2br($account->address) !!}</td>
					</tr>
					<tr>
						<td>State</td>
						<td>{{$account->state}}</td>
					</tr>
					<tr>
						<td>Country</td>
						<td>{{$account->country}}</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<div class="col-md-6">
		<div class="table-responsive">
			<table class="table table-condensed">
				<tbody>
					
					<tr>
						<td> Category </td>
						<td>{{ $account->category }}</td>
					</tr>
					<tr>
						<td>Industry</td>
						<td>{{$account->industry->name}}</td>
					</tr>
					<tr>
						<td>Account Manager </td>
						<td><i class="fa fa-user-o"></i> {{$account->manager->name}}</td>
					</tr>
					<tr>
						<td>Public Note</td>
						<td>{!! nl2br($account->note) !!}</td>
					</tr>
					<tr>
						<td>Currency</td>
						<td>{{ $account->currency ? $account->currency->name : '' }}</td>
					</tr>
					<tr>
						<td>Payment Terms</td>
						<td>{{ $payment->getTermByName($account->payment_terms)['label'] }} - {{ $payment->getTermByName($account->payment_terms)['description'] }}</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

</div>

<div class="row bg-white">
	
    <div class="col-md-12">
        <h4>Addition Information</h4>
        <hr class="horizonal-line-thick">
    </div>
    <div class="col-md-12">
       <ul class="nav nav-tabs nav-theme ">
            <li class="active"><a href="#nav-quotes" data-toggle="tab"> QUOTES </a></li>
            <li><a href="#nav-invoices" data-toggle="tab"> INVOICES </a></li>
            <li><a href="#nav-contacts" data-toggle="tab"> CONTACTS </a></li>
            <li><a href="#nav-call_logs" data-toggle="tab"> CALL LOGS </a></li>
        </ul>
        <div class="tab-content p-0">

            <div class="tab-pane fade clearfix active in" id="nav-quotes">                 

               	<div class="col-md-12 clearfix">
               		<div class="p-10">	
	               		<a class="btn" href="{{ route('tenant.crm.quote.create.account', [$tenant->domain, $account->id]) }}"> <i class="fa fa-plus"></i> New Quote </a>
	               	</div>
               </div>
               <div class="col-md-12">
               		<div class="table-responsive">
               			<table class="table table-hovered table-striped">
					    
					        <thead class="bg-success">
					            <tr>
					                <th class="width-20">S/N</th>
					                <th>Quote No#</th>
					                <th>Title</th>
					                <th>Account</th>
					                <th>Date</th>
					                <th>Amount</th>
					                <th>Valid Until</th>
					                <th>Status</th>
					            </tr>
					        </thead>

					        <tbody>
					            @foreach($account->quotes->take(50) as $index => $quote)
					            	<tr>
						                <td>{{$index+1}}</td>
					                    <td> <a href="{{ route('tenant.crm.quote.edit', [$tenant->domain, $quote->id]) }}"> {{$quote->quote_no}} </a> </td>
					                    <td> {{$quote->title}} </td>
					                    <td> <a href="{{ route('tenant.crm.account.show', [$tenant->domain, $quote->account->name]) }}"> {{$quote->account->name}} </a> </td>
					                    <td> {{ $quote->quote_date }} </td>
					                    <td> {{ $quote->currency ? $quote->currency->symbol : '' }} {{ number_format($quote->grand_total, 2) }} </td>
					                    <td> {{ $quote->expiration_date }} </td>
					                    <td> {!! $quote->status() !!} </td>  
					                </tr>
					            @endforeach
					        </tbody>
					    
					    </table>

               		</div>
               </div>

            </div>
            <div class="tab-pane fade clearfix" id="nav-invoices">                 

               <div class="col-md-12">
               		<div class="p-10">	
	               		<a class="btn" href="{{ route('tenant.crm.invoice.create', [$tenant->domain]) }}"> <i class="fa fa-plus"></i> New Invoice </a>
	               	</div>
               </div>
               <div class="col-md-12">
               		<div class="table-responsive">
               			<table class="table table-hovered table-striped">
					    
					        <thead class="bg-success">
					            <tr>
					                <th class="width-20">S/N</th>
					                <th>Invoice No#</th>
					                <th>Title</th>
					                <th>Account</th>
					                <th>Date</th>
					                <th>Amount</th>
					                <th>Valid Until</th>
					                <th>Status</th>
					            </tr>
					        </thead>

					        <tbody>
					            @foreach($account->invoices->take(50) as $index => $invoice)
					                <tr>
					                    <td>{{$index+1}}</td>
					                    <td> <a href="{{ route('tenant.crm.invoice.edit', [$tenant->domain, $invoice->id]) }}"> {{$invoice->invoice_no}} </a> </td>
					                    <td> {{$invoice->title}} </td>
					                    <td> <a href="{{ route('tenant.crm.account.show', [$tenant->domain, $invoice->account->name]) }}"> {{$invoice->account->name}} </a> </td>
					                    <td> {{ $invoice->invoice_date }} </td>
					                    <td> {{ $invoice->currency ? $invoice->currency->symbol : '' }} {{ number_format($invoice->grand_total, 2) }} </td>
					                    <td> {{ $invoice->expiration_date }} </td>
					                    <td> {!! $invoice->status() !!} </td>
					                </tr>
					            @endforeach
					        </tbody>
					    
					    </table>

               		</div>
               </div>
            </div>
            <div class="tab-pane fade clearfix" id="nav-contacts">                 
		        <div class="col-md-12 clearfix">
		        	<div class="p-10">	
	               		<a class="btn" href="{{ route('tenant.crm.contact.create', [$tenant->domain]) }}"> <i class="fa fa-plus"></i> New Contact </a>
	               	</div>
		        </div>
		        <div class="col-md-12">
		        	<div class="table-responsive">

					    <table class="table table-hovered table-striped">
					    
					        <thead>
					            <tr>
					                <th>S/N</th>
					                <th>Name</th>
					                <th>Account</th>
					                <th>Job Title</th>
					                <th>Phone</th>
					                <th>Email</th>
					            </tr>
					        </thead>

					        <tbody>
					            @foreach($account->contacts as $index => $contact)
					                <tr>
					                    <td>{{$index+1}}</td>
					                    <td> <a href="{{ route('tenant.crm.contact.show', [$tenant->domain, $contact->id]) }}"> {{$contact->name}} </a> </td>
					                    <td> {{ $contact->account->name }} </td>
					                    <td> {{ $contact->title }} </td>
					                    <td> {{ $contact->phone }} </td>
					                    <td> {{ $contact->email }} </td>
					                    <td> {{ $contact->status }} </td>
					                </tr>
					            @endforeach
					        </tbody>
					    
					    </table>

					</div>
		        </div>
            </div>
            <div class="tab-pane fade clearfix" id="nav-call_logs">                 
            	<div class="col-md-12">
            		
            	</div>
            	<div class="col-md-12 bg-white">
            		<div class="table-responsive">
            			<table class="table">
            				<thead>
            					<tr>
            						<th class="width-20"></th>
            						<th>Caller Num</th>
            						<th>Callee Num</th>
            						<th>Direction</th>
            						<th>Duration</th>
            						<th>Start</th>
            						<th>End</th>
            						<th></th>
            					</tr>
            				</thead>
            				<tbody>
            					@foreach($cdrs as $cdr)
            						<tr>
            							<td></td>
            							<td>{{ $cdr->caller_id_num }}</td>
            							<td>{{ $cdr->callee_id_num }}</td>
            							<td>{{ $cdr->direction }}</td>
            							<td>{{ $cdr->duration }}</td>
            							<td>{{ $cdr->start_time }}</td>
            							<td>{{ $cdr->end_time }}</td>
            							<td></td>
            						</tr>
            					@endforeach
            				</tbody>
            			</table>
            		</div>
            	</div>
            </div>

        </div>  
    </div>



</div>