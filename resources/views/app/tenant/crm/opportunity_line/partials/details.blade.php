<div class="row">
	<div class="m-15 p-t-15 bg-white clearfix">
		<div class="col-md-6">
			<div class="table-responsive">
				<table class="table ">
					<tbody>
						<tr>
							<th> Title</th>
							<td>  
								{{ $opportunity_line->name }} 
								<a href="javascript:;" class="btn btn-link pull-right btn-xs" ><i class="fa fa-edit" data-toggle="modal" data-target=".edit-opportunity-line-modal"></i></a>
							</td>
						</tr>
						<tr>
							<th> Contact Person </th>
		                    <td> {{ $opportunity_line->contact ? $opportunity_line->contact->name : '' }} </td>
						</tr>
						<tr>
							<th> Currency </th>
		                    <td> {{ $opportunity_line->currency ? $opportunity_line->currency->name : '' }} </td>
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
							<th>Speculated Worth</th>
		                    <td> {{ $opportunity_line->currency ? $opportunity_line->currency->symbol ." ". number_format($opportunity_line->worth, 2) : '' }} </td>
						</tr>
						<tr>
							<th>Description</th>
							<td>{!! nl2br($opportunity_line->description) !!}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

	</div>

</div>



@include('app.tenant.crm.opportunity_line.partials.form')

