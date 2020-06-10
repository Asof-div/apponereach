<div class="modal fade" id="EditIncidentModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form action="{{ route('operator.incident.update', [$incident->id]) }}" method="POST">
				{{ csrf_field() }}
				{{ method_field('PUT') }}

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Edit Incident</h4>
				</div>
				<div class="modal-body">

					<input type="hidden" name="incident_id" value="{{ $incident->id }}">
					<div class="form-group">
							<label>Name</label>
							<input type="text" name="name" value="{{ old('name') ? old('name') : $incident->name }}" class="form-control">
						</div>

						<div class="form-group">
							<label>Label</label>
							<input type="text" name="label" value="{{ old('label') ? old('label') : $incident->label }}" class="form-control">
						</div>

						<div class="form-group">
							<label>Initial Response Time </label>
							<div class="m-b-xs clearfix">
								<div class="col-md-8 no-p">
									<input type="text" name="initial_response_time" value="{{ old('initial_response_time') ? old('initial_response_time') : $incident->initial_response }}" class="form-control">
								</div>
								<div class="col-md-4 no-p">
									<select class="form-control" name="initial_response_unit">
										<option {{ old('initial_response_unit') == 'minute' || $incident->initial_response_unit == 'minute' ? 'selected' : '' }} value="minute">Minute</option>
										<option {{ old('initial_response_unit') == 'hour' || $incident->initial_response_unit == 'hour' ? 'selected' : '' }} value="hour">Hour</option>
										<option {{ old('initial_response_unit') == 'day' || $incident->initial_response_unit == 'day' ? 'selected' : '' }} value="day">Day</option>
									</select>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label>Expected Resolution Time </label>
							<div class="m-b-xs clearfix">
								<div class="col-md-8 no-p">
									<input type="text" name="expected_resolution_time" value="{{ old('expected_resolution_time') ? old('expected_resolution_time') : $incident->expected_resolution }}" class="form-control">
								</div>
								<div class="col-md-4 no-p">
									<select class="form-control" name="expected_resolution_unit">
										<option {{ old('expected_resolution_unit') == 'minute' || $incident->expected_resolution_unit == 'minute' ? 'selected' : '' }} value="minute">Minute</option>
										<option {{ old('expected_resolution_unit') == 'hour' || $incident->expected_resolution_unit == 'hour' ? 'selected' : '' }} value="hour">Hour</option>
										<option {{ old('expected_resolution_unit') == 'day' || $incident->expected_resolution_unit == 'day' ? 'selected' : '' }} value="day">Day</option>
									</select>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label>Escalation Interval Time </label>
							<div class="m-b-xs clearfix">
								<div class="col-md-8 no-p">
									<input type="text" name="escalation_interval_time" value="{{ old('escalation_interval_time') ? old('escalation_interval_time') : $incident->escalation_interval }}" class="form-control">
								</div>
								<div class="col-md-4 no-p">
									<select class="form-control" name="escalation_interval_unit">
										<option {{ old('escalation_interval_unit') == 'minute' || $incident->escalation_interval_unit == 'minute' ? 'selected' : '' }} value="minute">Minute</option>
										<option {{ old('escalation_interval_unit') == 'hour' || $incident->escalation_interval_unit == 'hour' ? 'selected' : '' }} value="hour">Hour</option>
										<option {{ old('escalation_interval_unit') == 'day' || $incident->escalation_interval_unit == 'day' ? 'selected' : '' }} value="day">Day</option>
									</select>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label>Priority</label>
							<select class="form-control" name="priority">
								<option {{ old('priority') == 'Low' || $incident->priority == 'Low' ? 'selected' : '' }} value="Low">Low</option>
								<option {{ old('priority') == 'Medium' || $incident->priority == 'Medium' ? 'selected' : '' }} value="Medium">Medium</option>
								<option {{ old('priority') == 'High' || $incident->priority == 'High' ? 'selected' : '' }} value="High">High</option>
								<option {{ old('priority') == 'Urgent' || $incident->priority == 'Urgent'  ? 'selected' : '' }} value="Urgent">Urgent</option>
							</select>
						</div>

						<div class="form-group">
							<label>Severity</label>
							<select class="form-control" name="severity">
								<option {{ old('severity') == 'Minor' || $incident->severity == 'Minor' ? 'selected' : '' }} value="Minor">Minor - Nuisance Issues</option>
								<option {{ old('severity') == 'Normal' || $incident->severity == 'Normal' ? 'selected' : '' }} value="Normal">Normal - User Impact</option>
								<option {{ old('severity') == 'Critical' || $incident->severity == 'Critical' ? 'selected' : '' }} value="Critical">Critical </option>
								<option {{ old('severity') == 'Emergency' || $incident->severity == 'Emergency' ? 'selected' : '' }} value="Emergency">Emergency</option>
								<option {{ old('severity') == 'Major' || $incident->severity == 'Major' ? 'selected' : '' }} value="Major">Major</option>
							</select>
						</div>

						<div class="form-group">
							<label> Lead Operator </label>
							<div class="m-b-xs clearfix">
								<select class="form-control" name="lead_operator">
									<option value=""> &dash; &dash; &dash; Select Lead Operator To Manage Ticket Incident &dash; &dash; &dash; </option>
									@foreach($operators as $operator)
										<option {{ old('lead_operator') == $operator->id || $incident->operator_id == $operator->id ? 'selected' : '' }} value="{{ $operator->id }}">{{ $operator->name }}</option>
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
										<option {{ old('lead_admin') == $admin->id || $incident->admin_id == $admin->id ? 'selected' : '' }} value="{{ $admin->id }}">{{ $admin->name }}</option>
									@endforeach
								</select>
							</div>
						</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Update changes</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="DeleteIncidentModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{ route('operator.incident.delete', [$incident->id]) }}" method="POST" permission="form">
				{{ csrf_field() }}
				{{ method_field('DELETE') }}

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Delete Incident</h4>
				</div>
				<div class="modal-body">
					<p>Are you sure you want to delete this incident?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-warning">Delete Incident</button>
				</div>

			</form>
		</div>
	</div>
</div>


<div class="modal fade" id="RevokeAdminModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{ route('operator.incident.admin.revoke', [$incident->id]) }}" method="POST">
				{{ csrf_field() }}
				{{ method_field('PUT') }}

				<input type="hidden" name="admin">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Revoke Admin</h4>
				</div>
				<div class="modal-body">
					<p>Are you sure you want to revoke this admin?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-warning">Revoke Admin</button>
				</div>

			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="RevokeOperatorModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{ route('operator.incident.operator.revoke', [$incident->id]) }}" method="POST">
				{{ csrf_field() }}
				{{ method_field('PUT') }}

				<input type="hidden" name="operator">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Revoke Operator</h4>
				</div>
				<div class="modal-body">
					<p>Are you sure you want to revoke this operator?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-warning">Revoke Operator</button>
				</div>

			</form>
		</div>
	</div>
</div>

