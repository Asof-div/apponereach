@extends('layouts.operator_sidebar')

@section('content')
	@section('title')
		Incident Details
	@endsection

	@section('breadcrumb')
		<li><a href="{{ route('operator.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('operator.incident.index') }}">Incidents</a></li>
        <li class="active">{{ $incident->name }}</li>
	@endsection

	<section>

		<div class="panel panel-default">

			<div class="panel-body">

				<div class="cool-md-12 clearfix">
					<div class="m-b-20 pull-left text-left">
						<a class="btn btn-primary" href="{{ route('operator.incident.index') }}"><i class="fa fa-list"></i> List Incident </a>
						@if(Gate::check('role.create'))
							<a class="btn btn-info" href="{{ route('operator.incident.create') }}"><i class="fa fa-plus"></i> Add Incident </a>
						@endif
					</div>
				
					<div class="m-b-20 pull-right text-right">
						@if ($incident->is_default == false && Gate::check('role.update'))
							<a data-toggle="modal" data-target="#EditIncidentModal" class="btn btn-default" href=""><i class="fa fa-pencil"></i></a>
						@endif
						@if ($incident->is_default == false && Gate::check('role.delete'))
							<a data-toggle="modal" data-target="#DeleteIncidentModal" class="btn btn-default" }}"><i class="fa fa-trash"></i></a>
						@endif
					</div>
					
				</div>
				<div class="col-md-12">
		            <hr style="background-color: #51BB8D; height: 3px;" />
					<div class="table-responsive clearfix">
	                    <table class="table details">
	                    	<tr class="highlight">
	                            <td class="field">Incident Name</td>
	                            <td>{{ $incident->name }}</td>
	                        </tr>

	                        <tr class="highlight">
	                            <td class="field">Incident Label</td>
	                            <td>{{ $incident->label }}</td>
	                        </tr>

	                        <tr class="highlight">
	                            <td class="field">Initial Response Time</td>
	                            <td>{{ $incident->initial_response_time }}</td>
	                        </tr>

	                        <tr class="highlight">
	                            <td class="field">Expected Resolution Time</td>
	                            <td>{{ $incident->expected_resolution_time }}</td>
	                        </tr>

	                        <tr class="highlight">
	                            <td class="field">Escalation Interval Time</td>
	                            <td>{{ $incident->escalation_interval_time }}</td>
	                        </tr>

	                        <tr class="highlight">
	                            <td class="field">Priority</td>
	                            <td>{{ $incident->priority }}</td>
	                        </tr>

	                        <tr class="highlight">
	                            <td class="field">Severity</td>
	                            <td>{{ $incident->severity }}</td>
	                        </tr>

	                        <tr class="highlight">
	                            <td class="field">Lead Operator</td>
	                            <td>{{ $incident->operator ? $incident->operator->name : ''}}</td>
	                        </tr>

	                        <tr class="highlight">
	                            <td class="field">Lead Admin </td>
	                            <td>{{ $incident->admin ? $incident->admin->name : '' }}</td>
	                        </tr>


	                    </table>
	                </div>
	            </div>
			</div>

		</div>


		<div class="row">
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-body">
					<form action="{{ route('operator.incident.operator.assign', [$incident->id]) }}" method="POST">
						{{ csrf_field() }}
						{{ method_field('PUT') }}

						<div class="form-group">
							<label>Add Operator Account</label>

							<select name="operator" class="form-control">
								<option value="">Select Operator Account</option>
								@foreach ($operators as $operator)
								<option value="{{ $operator->id }}">{{ $operator->name }}</option>
								@endforeach
							</select>
						</div>

						<div class="text-right">
							<button type="submit" class="btn btn-sm btn-primary">Add Operator</button>
						</div>
					</form>
					</div>
				</div>


				<div class="panel panel-default">
					<div class="panel-heading"> <h4 class="panel-title">Assigned Operators </h4> </div>

					<div class="panel-body">
						<div class="table-responsive">
							<table class="table">
								<tr>
									<th>Name</th>
									<th>Email</th>
								</tr>

								@foreach ($incident->operators as $operator)
								<tr>
									<td>{{ $operator->name }}</td>
									<td>{{ $operator->email }}</td>
									<td><a data-toggle="modal" data-target="#RevokeOperatorModal" data-operator-id="{{ $operator->id }}" href=""><i class="fa fa-remove text-danger"></i></a></td>
								</tr>
								@endforeach
							</table>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-body">
						<form action="{{ route('operator.incident.admin.assign', [$incident->id]) }}" method="POST">
							{{ csrf_field() }}
							{{ method_field('PUT') }}

							<div class="form-group">
								<label>Add Admin To Team</label>

								<select name="admin" class="form-control">
									<option value="">Select Admin</option>
									@foreach ($admins as $admin)
									<option value="{{ $admin->id }}">{{ $admin->name }}</option>
									@endforeach
								</select>
							</div>

							<div class="text-right">
								<button type="submit" class="btn btn-sm btn-primary">Add admin</button>
							</div>
						</form>
					</div>
				</div>


				<div class="panel panel-default">
					<div class="panel-heading"> <h4 class="panel-title">Assigned Support Admin</h4> </div>

					<div class="panel-body">
						<div class="table-responsive">
							<table class="table">
								<tr>
									<th>Name</th>
									<th>Email</th>
								</tr>

								@foreach ($incident->admins as $admin)
								<tr>
									<td>{{ $admin->name }}</td>
									<td>{{ $admin->email }}</td>
									@if ($incident->is_default == false && $admin->is_default == false)
									<td><a data-toggle="modal" data-target="#RevokeAdminModal" data-admin-id="{{ $admin->id }}" href=""><i class="fa fa-remove text-danger"></i></a></td>
									@endif
								</tr>
								@endforeach
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

	</section>

	@include('app.operator.incident.partials.modal')

@endsection

@section('extra-script')
	<script type="text/javascript">
		       
        $mn_list = $('.page-sidebar-inner ul.sidebar-nav > li.ticket');
        $mn_list.addClass('active open');
        $mn_list.find('.sub-menu > .incident-index').addClass('active');

		$('#RevokeAdminModal').on('show.bs.modal', function(evt) {
			var button = $(evt.relatedTarget);
			$('[name="admin"]').val(button.data('admin-id'));
		});

		$('#RevokeOperatorModal').on('show.bs.modal', function(evt) {
			var button = $(evt.relatedTarget);
			$('[name="operator"]').val(button.data('operator-id'));
		});
	</script>
@endsection
