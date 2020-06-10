<div class="modal fade" id="EditRoleModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{ route('tenant.account.role.update', [$tenant->domain, $role->id]) }}" method="POST">
				{{ csrf_field() }}
				{{ method_field('PUT') }}

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Edit Role</h4>
				</div>
				<div class="modal-body">

					<input type="hidden" name="tenant_id" value="{{ $tenant->id }}">

					<input type="hidden" name="role_id" value="{{ $role->id }}">

					<div class="form-group">
						<label>Name</label>
						<input type="text" name="name" value="{{ $role->name }}" class="form-control">
					</div>

					<div class="form-group">
						<label>Label</label>
						<input type="text" name="label" value="{{ $role->label }}" class="form-control">
					</div>

					<div class="form-group">
						<label>Description</label>
						<textarea name="description" class="form-control">{{ $role->description }}</textarea>
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Save changes</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="DeleteRoleModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{ route('tenant.account.role.delete', [$tenant->domain, $role->id]) }}" method="POST" permission="form">
				{{ csrf_field() }}
				{{ method_field('DELETE') }}

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Delete Role</h4>
				</div>
				<div class="modal-body">
					<p>Are you sure you want to delete this role?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-warning">Delete Role</button>
				</div>

			</form>
		</div>
	</div>
</div>


<div class="modal fade" id="RevokePermissionModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{ route('tenant.account.role.permission.revoke', [$tenant->domain, $role->id]) }}" method="POST">
				{{ csrf_field() }}
				{{ method_field('PUT') }}

				<input type="hidden" name="permission">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Revoke Permission</h4>
				</div>
				<div class="modal-body">
					<p>Are you sure you want to revoke this permission?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-warning">Revoke Permission</button>
				</div>

			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="RevokeAccountModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{ route('tenant.account.role.account.revoke', [$tenant->domain, $role->id]) }}" method="POST">
				{{ csrf_field() }}
				{{ method_field('PUT') }}

				<input type="hidden" name="account">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Revoke Account</h4>
				</div>
				<div class="modal-body">
					<p>Are you sure you want to revoke this account?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-warning">Revoke Account</button>
				</div>

			</form>
		</div>
	</div>
</div>

