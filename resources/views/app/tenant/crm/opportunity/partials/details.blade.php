<div class="row">
	<div class="col-md-6">
		<div class="table-responsive">
			<table class="table table-condensed">
				<tbody>
					<tr>
						<th> Title</th>
						<td>  
							{{ $opportunity->title }} 
							<a class="btn btn-link pull-right btn-xs" ><i class="fa fa-edit"></i></a>
						</td>
					</tr>
					<tr>
						<th> Account </th>
	                    <td> <a href="{{ url($tenant->domain.'/crm/account-show/'. $opportunity->account->name) }}"> {{$opportunity->account->name}} </a> </td>
					</tr>
					<tr>
						<th>Stage</th>
						<td>{{ $opportunity->stage }}</td>
					</tr>
					<tr>
						<th>Attention</th>
						<td>{{ $opportunity->attention }}</td>
					</tr>
					<tr>
						<th>Probability (%)</th>
						<td>{{ $opportunity->probability }} % </td> 
					</tr>
					<tr>
						<th>Source</th>
						<td>{{ $opportunity->source }}</td>
					</tr>
					<tr>
						<th>Competitor</th>
	                    <td> {{ $opportunity->competitor ? $opportunity->competitor->name : '' }} </td>
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
						<th>Close Date</th>
						<td>{{ $opportunity->close_date->format('y-m-d') }}</td>
					</tr>
					<tr>
						<th>Manager</th>
						<td> {{ $opportunity->manager ? $opportunity->manager->name : '' }} </td>
					</tr>
					<tr>
						<th>Status</th>
						<td>{{ $opportunity->status }}</td>
					</tr>
					<tr>
						<th>Worth</th>
	                    <td> {{ $opportunity->currency ? $opportunity->currency->symbol ." ". $opportunity->worth : ''}} </td>
					</tr>
					<tr>
						<th>Description</th>
						<td>{!! nl2br($opportunity->description) !!}</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>



	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

		<div id="pipe" class="col-xs-12">
			<ul>
				
				<li class="activestage"
				data-toggle="tooltip" data-placement="bottom" title="Itemize Solutions">
					<div>
						<span class="stagename">  Development </span>
					</div>
				</li>

				<li class="{{$opportunity->stage >= 2? 'activestage':''}}" 
				data-toggle="tooltip" data-placement="bottom" title="Proceed For Validation">
					<div>
						<span class="stagename">{{$opportunity->stage >3 ? 'Validated': 'Validation'}}</span>
						<span class="stagearrow"> Waiting For Details </span>
					</div>
				</li>
		
				<li class="{{$opportunity->stage >= 3? 'activestage':''}}" 
				data-toggle="tooltip" data-placement="bottom" title="Purchase Order">
					<div>
						<span class="stagename">{{$opportunity->stage >4 ? 'Received PO': 'Awaiting PO'}}</span>
						<span class="stagearrow"> Quotation </span>
					</div>
				</li>

				<li class="{{$opportunity->stage >= 4? 'activestage':''}}" 
				data-toggle="tooltip" data-placement="bottom" title="Sales Order">
					<div>
						<span class="stagename"> Waiting For PO </span>
						<span class="stagearrow"></span>
					</div>
				</li>

				<li class="{{$opportunity->stage >= 5? 'activestage':''}}" 
				data-toggle="tooltip" data-placement="bottom" title="Sales Order">
					<div>
						<span class="stagename"> Invoice </span>
						<span class="stagearrow"></span>
					</div>
				</li>

				<li class="{{$opportunity->stage >= 6? 'activestage':''}}" 
				data-toggle="tooltip" data-placement="bottom" title="Payment Confirmation">
					<div>
						<span class="stagename"> Won </span>
						<span class="stagearrow"></span>
					</div>
				</li>

				
			</ul>
		</div>

	</div>

</div>



<div class="row  p-t-10">
	


       <!-- begin col-12 -->
    <div class="col-md-12">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#activities-default" data-toggle="tab" aria-expanded="true">Activites</a></li>
			<li class=""><a href="#tasks-default" data-toggle="tab" aria-expanded="false"> Task </a></li>
			<li class=""><a href="#opportunity-default" data-toggle="tab" aria-expanded="false"> Opportunity Line </a></li>
			{{-- <li class=""><a href="#quotes-default" data-toggle="tab" aria-expanded="false"> Quotes </a></li> --}}
			{{-- <li class=""><a href="#documents-default" data-toggle="tab" aria-expanded="false">Invoices</a></li> --}}
			<li class=""><a href="#documents-default" data-toggle="tab" aria-expanded="false">Contacts</a></li>
			<li class=""><a href="#back_logs-default" data-toggle="tab" aria-expanded="false">Back Logs</a></li>

		</ul>
		<div class="tab-content">
			<div class="tab-pane fade active in" id="activities-default">
				<div class="m-t-10 clearfix"> 
					<span class="h3"> Activities </span>  
					<span class="pull-right"> <button type="button" class="btn btn-primary"> <i class="fa fa-comment"></i> Leave Your Comment</button></span>
				</div>
			

				<div class="clearfix">
						
				</div>
			</div>
			<div class="tab-pane fade" id="tasks-default">
				<div class="m-t-10 clearfix"> 
					<span class="h3"><i class="fa fa-tasks"></i> Task </span>  
					<span class="pull-right"> <button type="button" class="btn btn-primary"> <i class="fa fa-tasks"></i> Create Sub Task </button></span>
				</div>
			</div>
			<div class="tab-pane fade" id="opportunity-default">
				<div class="m-t-10 clearfix"> 
					<span class="h3"><i class="fa fa-files-o"></i> Add New Opportunity Line </span>  
					<span class="pull-right"> <a href="javascript:;" data-toggle='modal' data-target='.add-opportunity-line-modal' class="btn btn-primary"> Add Opportunity Line </a> </span>
				</div>

				<div class="table-responsive clearfix">
					<table class="table">
						<thead>
							<tr>
								<th>S/N</th>
								<th>Title</th>
								<th>Description</th>
								<th>Worth</th>
								<th>Contact Person</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody class="opportunity-lines-table">
							@foreach($opportunity->lines as $index => $line)
								<tr>
									<td>{{ $index +1 }}</td>
									<td>{{ $line->name }}</td>
									<td>{!! nl2br($line->description) !!}</td>
									<td>{{ $line->currency ? $line->currency->symbol ." ". number_format( $line->price, 2) : '' }}</td>
									<td>{{ $line->contact ? $line->contact->name : '' }}</td>
									<td>
										<button class="btn btn-success btn-xs">Progress Report</button>
										<a class="btn btn-primary btn-xs" href="{{ route('tenant.crm.opportunity.opportunity-line.show', [$tenant->domain, $opportunity->id, $line->id]) }}">Generate Quote</a>
										<button class="btn btn-danger btn-xs">Delete</button>
									</td>

								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
			{{-- <div class="tab-pane fade" id="quotes-default">
				<div class="m-t-10 clearfix"> 
					<span class="h3"><i class="fa fa-files-o"></i> Shared Quote </span>  
					<span class="pull-right"> <a href="{{ route('tenant.crm.opportunity.quote.create', [$tenant->domain, $opportunity->id]) }}" class="btn btn-primary"> New Quote </a> </span>
				</div>
			</div> --}}
			<div class="tab-pane fade" id="back_logs-default">
				<div class="m-t-10 clearfix"> 
					<span class="h3"></i> Back Logs </span>  

				</div>

			</div>

		</div>

		

	</div>
    

</div>


