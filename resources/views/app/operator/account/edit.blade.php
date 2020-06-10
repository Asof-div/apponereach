@extends('layouts.admin')

@section('content')
	@section('title')
		Edit Account
	@endsection

	@section('breadcrumb')
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
		<li><a href="{{ route('admin.account.index') }}">Accounts</a></li>
		<li class="active">Edit Account</li>
    @endsection

	<section class="content-card">
		<form name="frmEditAdmin" method="POST" action="{{ route('admin.account.update', [$admin->id]) }}">
			{{ csrf_field() }}
			{{ method_field('PUT') }}

			<div class="form-group">
				<label>First Name</label>
				<input type="text" name="first_name" value="{{ $admin->first_name }}" class="form-control">
			</div>

			<div class="form-group">
				<label>Last Name</label>
				<input type="text" name="last_name" value="{{ $admin->last_name }}" class="form-control">
			</div>

			<div class="form-group">
				<label>Email Address</label>
				<input type="text" name="email" value="{{ $admin->email }}" class="form-control">
			</div>

			<hr>

			<div class="text-right">
				<button type="submit" class="btn btn-primary">Save Changes</button>
			</div>
		</form>
	</section>
@endsection
