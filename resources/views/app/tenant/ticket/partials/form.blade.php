<form name="frmAddTicket" id="ticket_form" method="POST" action="{{ route('tenant.ticket.store', [$tenant->domain]) }}">
	{{ csrf_field() }}
	<input type="hidden" name="tenant_id" value="{{ $tenant->id }}">
	<div class="form-group clearfix">
		<label>Title</label>
		<input type="text" name="title" value="{{ old('title') }}" class="form-control">
	</div>

	<div class="form-group clearfix">
		<label>Incident Type</label>
		<select class="form-control" name="incident_type">
			@foreach($incidents as $incident)
				<option value="{{ $incident->id }}" {{ old('incident_type') == $incident->id ? 'selected' : '' }}> {{ $incident->label }} </option>
			@endforeach
		</select>
	</div>

	<div class="form-group clearfix">
		<label> Body / Message </label>
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
	@if(Gate::check('tenant.ticket.create'))
	<div class="form-group clearfix" style="border: 1px black solid; padding: 5px;">
        <button type="submit" class="btn pull-right btn-transparent btn-primary "> Submit Ticket</button>
    </div>
	@else
		<p class="f-s-15 text-danger"> You do not have the permission to create a ticket. Please Contact you Administrator.</p>
	@endif

</form>

