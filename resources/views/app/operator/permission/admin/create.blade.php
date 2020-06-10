@extends('layouts.admin')

@section('content')
	@section('title')
		Add Permission
	@endsection

	@section('breadcrumb')
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.permission.admin.index') }}">Admin Permissions</a></li>
        <li class="active">Create</li>
    @endsection

	<div class="panel panel-default">
		<div class="panel-body">
			<form name="frmAddPermission" method="POST" action="{{ route('admin.permission.admin.store') }}">
				{{ csrf_field() }}

				<div class="form-group">
					<label>Name</label>
					<input type="text" name="name" value="{{ old('name') }}" class="form-control">
				</div>

				<div class="form-group">
					<label>Label</label>
					<input type="text" name="label" value="{{ old('label') }}" class="form-control">
				</div>

				<div class="form-group">
					<label>Description</label>
					<textarea name="description" class="form-control">{{ old('description') }}</textarea>
				</div>

				<div class="text-right">
					<button type="submit" class="btn btn-primary">Create</button>
				</div>
			</form>
		</div>
	</div>
@endsection
