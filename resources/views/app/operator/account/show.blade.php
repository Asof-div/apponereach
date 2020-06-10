@extends('layouts.admin')

@section('content')
	@section('title')
		Admin Details
	@endsection

	@section('breadcrumb')
		<li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.account.index') }}">Admin Accounts</a></li>
        <li class="active">{{ $admin->name }}</li>
	@endsection

	<section>

		<div class="panel panel-default">
			<div class="panel-body">

				<div class="table-responsive">
                    <table class="table details">
                    	<tr class="highlight">
                            <td class="field">Name</td>
                            <td>{{ $admin->name }}</td>
                        </tr>

                        <tr class="highlight">
                            <td class="field">Email</td>
                            <td>{{ $admin->email }}</td>
                        </tr>
                    </table>
                </div>
			</div>

		</div>

		<div class="panel panel-default">
			<div class="panel-body">
				<form action="{{ route('admin.account.role.assign', [$admin->id]) }}" method="POST">
					{{ csrf_field() }}
					{{ method_field('PUT') }}

					<div class="form-group">
						<label>Assign Role To Account</label>

						<select name="role" class="form-control select-picker" data-live-search="true">
							<option value="">Select Role</option>
							@foreach ($roles as $role)
							<option value="{{ $role->id }}">{{ $role->name }}</option>
							@endforeach
						</select>
					</div>

					<div class="text-right">
						<button type="submit" class="btn btn-sm btn-primary">Assign Role</button>
					</div>
				</form>
			</div>
		</div>


		<div class="panel panel-default">
			<div class="panel-heading"> <h4 class="panel-title">Account Roles</h4> </div>

			<div class="panel-body">
				<div class="table-responsive">
					<table class="table">
						<tr>
							<th>Name</th>
							<th>Label</th>
							<th>Description</th>
						</tr>

						@foreach ($admin->roles as $role)
						<tr>
							<td>{{ $role->name }}</td>
							<td>{{ $role->label }}</td>
							<td>{{ $role->description }}</td>
							@if ($role->is_default == false)
							<td><a data-toggle="modal" data-target="#RevokeRoleModal" data-role-id="{{ $role->id }}" href=""><i class="fa fa-remove text-danger"></i></a></td>
							@endif
						</tr>
						@endforeach
					</table>
				</div>
			</div>
		</div>

	</section>

	@include('admin.account.includes.modal')

@endsection

@section('extra-scripts')
<script type="text/javascript">
	$('.select-picker').selectpicker();

	$('#RevokeRoleModal').on('show.bs.modal', function(evt) {
		var button = $(evt.relatedTarget);
		$('[name="role"]').val(button.data('role-id'));
	});
</script>
@endsection
