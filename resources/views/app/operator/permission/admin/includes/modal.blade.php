<div class="modal fade" id="EditPermissionModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{ route('admin.permission.admin.update', [$permission->id]) }}" method="POST">
				{{ csrf_field() }}
				{{ method_field('PUT') }}

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Edit Permission</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label>Name</label>
						<input type="text" name="name" value="{{ $permission->name }}" class="form-control">
					</div>

					<div class="form-group">
						<label>Label</label>
						<input type="text" name="label" value="{{ $permission->label }}" class="form-control">
					</div>

					<div class="form-group">
						<label>Description</label>
						<textarea name="description" class="form-control">{{ $permission->description }}</textarea>
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

<div class="modal fade" id="DeletePermissionModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{ route('admin.permission.admin.delete', [$permission->id]) }}" method="POST">
				{{ csrf_field() }}
				{{ method_field('DELETE') }}

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Delete Permission</h4>
				</div>
				<div class="modal-body">
					<p>Are you sure you want to delete this permission?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-warning">Delete Permission</button>
				</div>

			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="RevokeFromRoleModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{ route('admin.permission.admin.role.revoke', [$permission->id]) }}" method="POST">
				{{ csrf_field() }}
				{{ method_field('PUT') }}

				<input type="hidden" name="role">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Revoke Role</h4>
				</div>
				<div class="modal-body">
					<p>Are you sure you want to revoke the permission from this role?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-warning">Revoke From Role</button>
				</div>

			</form>
		</div>
	</div>
</div>
