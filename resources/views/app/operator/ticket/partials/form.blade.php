<form name="frmAddTicket" method="POST" action="{{ route('operator.ticket.store') }}" accept-charset="utf-8" enctype="multipart/form-data">
	{{ csrf_field() }}

	<div class="form-group">
		<label>Customer </label>
		<select name="customer" class="form-control">
			<option value=""> Select Customer Who you are creating ticket on behave. </option>
			@foreach($customers as $customer)
				<option value="{{ $customer->id }}">{{ $customer->name }}</option>
			@endforeach
		</select>
	</div>

	<div class="form-group clearfix">
		<label>Title <i class="fa fa-asterisk text-danger"></i></label>
		<input type="text" name="title" value="{{ old('title') }}" class="form-control">
	</div>

	<div class="form-group clearfix">
		<label>Incident Type <i class="fa fa-asterisk text-danger"></i></label>
		<select class="form-control" name="incident_type">
			@foreach($incidents as $incident)
				<option value="{{ $incident->id }}" {{ old('incident_type') == $incident->id ? 'selected' : '' }}> {{ $incident->label }} </option>
			@endforeach
		</select>
	</div>

	<div class="form-group clearfix">
		<label> Body / Message <i class="fa fa-asterisk text-danger"></i></label>
		<div class="m-b-xs clearfix">
			<textarea class="form-control" name="body" rows="10"></textarea>
		</div>
	</div>

    <div class="form-group" style="border: 1px black solid; padding: 4px;">

	    <button class="btn btn-success btn-transparent btn-xs" onclick="event.preventDefault(); addresource('resource-container');"> <i class="fa fa-plus"></i> Add Resources </button> (Size 3MB) (Max 5)

	</div>

	<div class="form-group">

	    <div id="resource-container" style="background: #47a3da;"></div>

	</div>

	<hr>
	@if(Gate::check('ticket.create'))
	<div class="form-group clearfix" style="border: 1px black solid; padding: 5px;">
        <label class="f-s-15"><input type="checkbox" name="allow_tenant"> Make Resource Visible <i class="fa fa-eye"></i> to customer </label>
        <button type="submit" class="btn pull-right btn-transparent btn-primary "> Submit Ticket</button>
    </div>
	@else    
		<div class="col-md-12 bg-danger clearfix m-t-30 p-15 m-r-0 m-l-0">
	        <p class="f-s-16 p-10"> You do not have the permission to perform this action. Please Contact you Administrator.</p>
	    </div>
	@endif
</form>

