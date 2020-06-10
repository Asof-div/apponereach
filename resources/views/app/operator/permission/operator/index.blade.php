@extends('layouts.admin')

@section('content')
	@section('title')
		Operator Permissions ({{ $permissions->total() }})
	@endsection

	@section('breadcrumb')
		<li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
      	<li class="active">Permissions</li>
	@endsection

	<div class="panel panel-default">
		<div class="panel-body">

			<div class="text-right m-b-20">
				<a class="btn btn-primary" href="{{ route('admin.permission.operator.create') }}"><i class="fa fa-plus"></i> Add Permission</a>
			</div>

			<table class="table table-stripped table-hover">
				<thead>
					<tr>
						<th>Name</th>
						<th>Label</th>
						<th>Description</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@forelse($permissions as $permission)
						<tr>
							<td>{{ $permission->name }}</td>
							<td>{{ $permission->label }}</td>
							<td>{{ $permission->description }}</td>
							<td><a href="{{ route('admin.permission.operator.show', [$permission->id]) }}"> <i class="fa fa-eye"></i> </a></td>
						</tr>
					@empty
						<tr><td class="text-center" colspan="4"> No Permissions Added</td></tr>
					@endforelse
				</tbody>
			</table>

		</div>
	</div>

	{{ $permissions->links() }}
@endsection
