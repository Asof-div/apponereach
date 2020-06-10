<form name="frmAddAdminRole" method="POST" action="{{ route('operator.incident.store') }}">
	{{ csrf_field() }}

	<div class="form-group">
		<label>Name</label>
		<input type="text" name="name" value="{{ old('name') }}" class="form-control">
	</div>

	<div class="form-group">
		<label>Label</label>
		<input type="text" name="label" value="{{ old('label') }}" class="form-control">
	</div>

	<div class="form-group">
		<label>Initial Response Time </label>
		<div class="m-b-xs clearfix">
			<div class="col-md-8 no-p">
				<input type="text" name="initial_response_time" value="{{ old('initial_response_time') }}" class="form-control">
			</div>
			<div class="col-md-4 no-p">
				<select class="form-control" name="initial_response_unit">
					<option {{ old('initial_response_unit') == 'minute' ? 'selected' : '' }} value="minute">Minute</option>
					<option {{ old('initial_response_unit') == 'hour' ? 'selected' : '' }} value="hour">Hour</option>
					<option {{ old('initial_response_unit') == 'day' ? 'selected' : '' }} value="day">Day</option>
				</select>
			</div>
		</div>
	</div>

	<div class="form-group">
		<label>Expected Resolution Time </label>
		<div class="m-b-xs clearfix">
			<div class="col-md-8 no-p">
				<input type="text" name="expected_resolution_time" value="{{ old('expected_resolution_time') }}" class="form-control">
			</div>
			<div class="col-md-4 no-p">
				<select class="form-control" name="expected_resolution_unit">
					<option {{ old('expected_resolution_unit') == 'minute' ? 'selected' : '' }} value="minute">Minute</option>
					<option {{ old('expected_resolution_unit') == 'hour' || old('expected_resolution_unit') == '' ? 'selected' : '' }} value="hour">Hour</option>
					<option {{ old('expected_resolution_unit') == 'day' ? 'selected' : '' }} value="day">Day</option>
				</select>
			</div>
		</div>
	</div>

	<div class="form-group">
		<label>Escalation Interval Time </label>
		<div class="m-b-xs clearfix">
			<div class="col-md-8 no-p">
				<input type="text" name="escalation_interval_time" value="{{ old('escalation_interval_time') }}" class="form-control">
			</div>
			<div class="col-md-4 no-p">
				<select class="form-control" name="escalation_interval_unit">
					<option {{ old('escalation_interval_unit') == 'minute' ? 'selected' : '' }} value="minute">Minute</option>
					<option {{ old('escalation_interval_unit') == 'hour' || old('escalation_interval_unit') == '' ? 'selected' : '' }} value="hour">Hour</option>
					<option {{ old('escalation_interval_unit') == 'day' ? 'selected' : '' }} value="day">Day</option>
				</select>
			</div>
		</div>
	</div>

	<div class="form-group">
		<label>Priority</label>
		<select class="form-control" name="priority">
			<option {{ old('priority') == 'Low' ? 'selected' : '' }} value="Low">Low</option>
			<option {{ old('priority') == 'Medium' || old('priority') == '' ? 'selected' : '' }} value="Medium">Medium</option>
			<option {{ old('priority') == 'High' ? 'selected' : '' }} value="High">High</option>
			<option {{ old('priority') == 'Urgent' ? 'selected' : '' }} value="Urgent">Urgent</option>
		</select>
	</div>

	<div class="form-group">
		<label>Severity</label>
		<select class="form-control" name="severity">
			<option {{ old('severity') == 'Minor' ? 'selected' : '' }} value="Minor">Minor - Nuisance Issues</option>
			<option {{ old('severity') == 'Normal' || old('severity') == '' ? 'selected' : '' }} value="Normal">Normal - User Impact</option>
			<option {{ old('severity') == 'Critical' ? 'selected' : '' }} value="Critical">Critical </option>
			<option {{ old('severity') == 'Emergency' ? 'selected' : '' }} value="Emergency">Emergency</option>
			<option {{ old('severity') == 'Major' ? 'selected' : '' }} value="Major">Major</option>
		</select>
	</div>

	<div class="form-group">
		<label> Lead Operator </label>
		<div class="m-b-xs clearfix">
			<select class="form-control" name="lead_operator">
				<option value=""> &dash; &dash; &dash; Select Lead Operator To Manage Ticket Incident &dash; &dash; &dash; </option>
				@foreach($operators as $operator)
					<option {{ old('lead_operator') == $operator->id ? 'selected' : '' }} value="{{ $operator->id }}">{{ $operator->name }}</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="form-group">
		<label> Lead Admin </label>
		<div class="m-b-xs clearfix">
			<select class="form-control" name="lead_admin">
				<option value=""> &dash; &dash; &dash; Select Lead Admin To Manage Ticket Incident &dash; &dash; &dash; </option>
				@foreach($admins as $admin)
					<option {{ old('lead_admin') == $admin->id ? 'selected' : '' }} value="{{ $admin->id }}">{{ $admin->name }}</option>
				@endforeach
			</select>
		</div>
	</div>


	<hr>
	@if(Gate::check('role.create'))
	<div class="text-right">
		<button type="submit" class="btn btn-primary">Create</button>
	</div>
	@endif
</form>

