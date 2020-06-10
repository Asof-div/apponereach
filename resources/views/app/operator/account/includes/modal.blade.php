<div class="modal fade" id="RevokeRoleModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{ route('admin.account.role.revoke', [$admin->id]) }}" method="POST">
				{{ csrf_field() }}
				{{ method_field('PUT') }}

				<input type="hidden" name="role">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Revoke Role</h4>
				</div>
				<div class="modal-body">
					<p>Are you sure you want to revoke the role from this account?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-warning">Revoke Role</button>
				</div>

			</form>
		</div>
	</div>
</div>
