@extends('layouts.admin')

@section('content')

	@section('title')
		Admin Accounts ({{ $admins->total() }})
	@endsection

	@section('breadcrumb')
		<li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
		<li class="active">Accounts</li>
	@endsection

<div class="row">
	<div class="col-xs-12">

		<div class="panel panel-default">
			<div class="panel-body">

				@if (Gate::allows('manage-admin-account'))
				<div class="text-right m-b-20">
					<a class="btn btn-primary cta-btn" href="{{ route('admin.register') }}"> <i class="fa fa-plus"></i> Create Account</a>
				</div>
				@endif

				<table class="table table-hover">
					<tr>
						<th>First Name</th>
						<th>Last Name</th>
						<th>Email</th>
						<th>Added On</th>
						<th>Action</th>
					</tr>

					@forelse ($admins as $admin)
						<tr>
							<td>{{ $admin->first_name }}</td>
							<td>{{ $admin->last_name }}</td>
							<td>{{ $admin->email }}</td>
							<td>{{ $admin->created_at->format('d M, Y') }}</td>
							@if (Gate::allows('manage-admin-account'))
							<td><a href="{{ route('admin.account.edit', [$admin->id]) }}"> <i class="fa fa-edit"></i> </a></td>
							@endif
							<td><a href="{{ route('admin.account.show', [$admin->id]) }}"> <i class="fa fa-eye"></i> </a></td>
							@if ($admin->id !== Auth::guard('admin')->user()->id)<td><a data-toggle="modal" data-target="#DeleteAdminModal" data-admin-id="{{ $admin->id }}" href=""> <i class="fa fa-trash"></i> </a></td>@endif
						</tr>
					@empty

					@endforelse
				</table>

			</div>
		</div>

		{{ $admins->links() }}

	</div>

</div>


<div class="modal fade" id="DeleteAdminModal">
	<div class="modal-dialog">
		<form name="frmDeleteAdmin" method="POST" action="{{ route('admin.account.delete') }}">
			{{ csrf_field() }}
			{{ method_field('DELETE') }}

			<input type="hidden" name="id">

			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Delete Admin</h4>
				</div>
				<div class="modal-body">
					<p>Are you sure you want to delete this admin?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-warning">Delete</button>
				</div>
			</div>
		</form>
	</div>
</div>

@endsection

@section('extra-scripts')
<script type="text/javascript">
	$('#DeleteAdminModal').on('show.bs.modal', function(evt) {
		var button = $(evt.relatedTarget);

		$('#DeleteAdminModal [name="id"]').val(button.data('admin-id'));
	})
</script>
@endsection
