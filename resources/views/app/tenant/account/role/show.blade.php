@extends('layouts.tenant_sidebar')

@section('content')
	@section('title')
		Role Details
	@endsection

	@section('breadcrumb')
	
        <li><a href="{{ route('tenant.account.role.index', [$tenant->domain]) }}">Roles</a></li>
        <li class="active">{{ $role->name }}</li>
	@endsection

	<section>

		<div class="panel panel-default">

			<div class="panel-body">

				<div class="cool-md-12 clearfix">
					<div class="m-b-20 pull-left text-left">
						<a class="btn btn-primary" href="{{ route('tenant.account.role.index', [$tenant->domain]) }}"><i class="fa fa-list"></i> List Role </a>
						@if(Gate::check('tenant.role.create'))
							<a class="btn btn-info" href="{{ route('tenant.account.role.create', [$tenant->domain]) }}"><i class="fa fa-plus"></i> Add Role </a>
						@endif
					</div>
				
					<div class="m-b-20 pull-right text-right">
						@if ($role->editable() && Gate::check('tenant.role.update'))
							<a data-toggle="modal" data-target="#EditRoleModal" class="btn btn-default" href=""><i class="fa fa-pencil"></i></a>
						@endif
						@if ($role->editable() && Gate::check('tenant.role.delete'))
							<a data-toggle="modal" data-target="#DeleteRoleModal" class="btn btn-default" }}"><i class="fa fa-trash"></i></a>
						@endif
					</div>
					
				</div>
				<div class="col-md-12">
		            <hr class="horizonal-line-thick" />
					<div class="table-responsive clearfix">
	                    <table class="table details">
	                    	<tr class="highlight">
	                            <td class="field">Role Name</td>
	                            <td>{{ $role->name }}</td>
	                        </tr>

	                        <tr class="highlight">
	                            <td class="field">Role Label</td>
	                            <td>{{ $role->label }}</td>
	                        </tr>

	                        <tr class="highlight">
	                            <td class="field">Description</td>
	                            <td>{{ $role->description }}</td>
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
					<form action="{{ route('tenant.account.role.account.assign', [$tenant->domain, $role->id]) }}" method="POST">
						{{ csrf_field() }}
						{{ method_field('PUT') }}

						<div class="form-group">
							<label>Add Role To tenant Account</label>

							<select name="user" class="form-control">
								<option value="">Select User Account</option>
								@foreach ($users as $user)
									<option value="{{ $user->id }}">{{ $user->name }}</option>
								@endforeach
							</select>
						</div>

						<div class="text-right">
							<button type="submit" class="btn btn-sm btn-primary">Add Account</button>
						</div>
					</form>
					</div>
				</div>


				<div class="panel panel-default">
					<div class="panel-heading"> <h4 class="panel-title">Assigned Accounts</h4> </div>

					<div class="panel-body">
						<div class="table-responsive">
							<table class="table">
								<tr>
									<th>Name</th>
									<th>Email</th>
								</tr>

								@foreach ($role->users as $user)
								<tr>
									<td>{{ $user->name }}</td>
									<td>{{ $user->email }}</td>
									<td><a data-toggle="modal" data-target="#RevokeAccountModal" data-user-id="{{ $user->id }}" href=""><i class="fa fa-remove text-danger"></i></a></td>
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
						<form action="{{ route('tenant.account.role.permission.assign', [$tenant->domain, $role->id]) }}" method="POST">
							{{ csrf_field() }}
							{{ method_field('PUT') }}

							<div class="form-group">
								<label>Add Permission To Role</label>

								<select name="permission" class="form-control">
									<option value="">Select Permission</option>
									@foreach ($permissions as $permission)
										<option value="{{ $permission->id }}">{{ $permission->description }}</option>
									@endforeach
								</select>
							</div>

							@if ($role->editable() && Gate::check('tenant.permission.assign') )
							<div class="text-right">
								<button type="submit" class="btn btn-sm btn-primary">Add Permission</button>
							</div>
							@else 
								<p class="f-s-15 text-danger"> You do not have the permission to perform this action. Please Contact you Administrator.</p>
							@endif
						</form>
					</div>
				</div>


				<div class="panel panel-default">
					<div class="panel-heading"> <h4 class="panel-title">Permissions</h4> </div>

					<div class="panel-body">
						<div class="table-responsive">
							<table class="table">
								<tr>
									<th>Label</th>
									<th>Description</th>
								</tr>

								@foreach ($role->permissions as $permission)
								<tr>
									<td>{{ $permission->label }}</td>
									<td>{{ $permission->description }}</td>
									@if ($role->editable() && Gate::check('tenant.permission.withdraw') )
										<td><a data-toggle="modal" data-target="#RevokePermissionModal" data-permission-id="{{ $permission->id }}" href=""><i class="fa fa-remove text-danger"></i></a></td>
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

	@include('app.tenant.account.role.partials.modal')

@endsection

@section('extra-script')
	<script type="text/javascript">

        $mn_list = $('.sidebar ul.nav > li.nav-account');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-roles').addClass('active');

		$('#RevokePermissionModal').on('show.bs.modal', function(evt) {
			var button = $(evt.relatedTarget);
			$('[name="permission"]').val(button.data('permission-id'));
		});

		$('#RevokeAccountModal').on('show.bs.modal', function(evt) {
			var button = $(evt.relatedTarget);
			$('[name="account"]').val(button.data('user-id'));
		});
	</script>
@endsection
