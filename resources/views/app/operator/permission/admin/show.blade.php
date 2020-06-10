@extends('layouts.admin')

@section('content')
	@section('title')
		Permission Details
	@endsection

	@section('breadcrumb')
		<li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.permission.admin.index') }}">Permissions</a></li>
        <li class="active">{{ $permission->name }}</li>
	@endsection

	<section>

		<div class="panel panel-default">
			<div class="panel-body">

				@if ($permission->is_default == false)
				<div class="m-b-20 text-right">
					<a data-toggle="modal" data-target="#EditPermissionModal" class="btn btn-default" href=""><i class="fa fa-pencil"></i></a>

					<a data-toggle="modal" data-target="#DeletePermissionModal" class="btn btn-default" href="{{ route('admin.permission.admin.edit', [$permission->id]) }}"><i class="fa fa-remove"></i></a>
				</div>
				@endif

				<div class="table-responsive">
                    <table class="table details">
                    	<tr class="highlight">
                            <td class="field">Permission Name</td>
                            <td>{{ $permission->name }}</td>
                        </tr>

                        <tr class="highlight">
                            <td class="field">Permission Label</td>
                            <td>{{ $permission->label }}</td>
                        </tr>

                        <tr class="highlight">
                            <td class="field">Description</td>
                            <td>{{ $permission->description }}</td>
                        </tr>
                    </table>
                </div>
			</div>

		</div>

		<div class="panel panel-default">
			<div class="panel-body">
				<form action="{{ route('admin.permission.admin.role.assign', [$permission->id]) }}" method="POST">
					{{ csrf_field() }}
					{{ method_field('PUT') }}

					<div class="form-group">
						<label>Add Permission To Role</label>

						<select name="role" class="form-control select-picker" data-live-search="true">
							<option value="">Select Role</option>
							@foreach ($roles as $role)
							<option value="{{ $role->id }}">{{ $role->label }}</option>
							@endforeach
						</select>
					</div>

					<div class="text-right">
						<button type="submit" class="btn btn-sm btn-primary">Add Permission</button>
					</div>
				</form>
			</div>
		</div>


		<div class="panel panel-default">
			<div class="panel-heading"> <h4 class="panel-title">Roles</h4> </div>

			<div class="panel-body">
				<div class="table-responsive">
					<table class="table">
						<tr>
							<th>Name</th>
							<th>Label</th>
							<th>Description</th>
						</tr>

						@foreach ($permission->roles as $role)
						<tr>
							<td>{{ $role->name }}</td>
							<td>{{ $role->label }}</td>
							<td>{{ $role->description }}</td>
							@if ($permission->is_default == false && $role->is_default == false)
							<td><a data-toggle="modal" data-target="#RevokeFromRoleModal" data-role-id="{{ $role->id }}" href=""><i class="fa fa-remove text-danger"></i></a></td>
							@endif
						</tr>
						@endforeach
					</table>
				</div>
			</div>
		</div>

	</section>

	@include('admin.permission.admin.includes.modal')

@endsection

@section('extra-scripts')
<script type="text/javascript">
	$('.select-picker').selectpicker();

	$('#RevokeFromRoleModal').on('show.bs.modal', function(evt) {
		var button = $(evt.relatedTarget);
		$('[name="role"]').val(button.data('role-id'));
	});
</script>
@endsection
