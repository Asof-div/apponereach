@extends('layouts.tenant_sidebar')

@section('title')
	Create Role
@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.account.role.index', [$tenant->domain]) }}"> Roles</a></li>
    <li class="active">Create</li>

@endsection

@section('content')

    <div class="panel ">
    	<div class="panel-heading"> 
            <div class="panel-title">
                <span class="h3"> &nbsp; Add New Role  </span> 
	            
	            <span class="pull-right m-r-10">
	                <a href="{{ route('tenant.account.role.index', [$tenant->domain]) }}" class="btn btn-outline-default"> <i class="fa fa-list"></i> Roles <span class="text-primary"> </span> </a>
	            </span>
            </div>
            <hr class="horizonal-line-thick">
        </div>

		<div class="panel-body">

			<form name="frmAddAdminRole" method="POST" action="{{ route('tenant.account.role.store', [$tenant->domain]) }}">
				{{ csrf_field() }}
				<input type="hidden" name="tenant_id" value="{{ $tenant->id }}">

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

			    <div class="p-20 col-md-12 bg-white m-b-15">
				    <div class="clearfix m-b-15 m-l-15"> <span class="h4 text-primary"> Permissions </span> </div>
				    <span></span>


				    <div class="col-md-12 col-sm-12">


				        @foreach($permissions->chunk(3) as $permChunk)
	                    <div class="row">
	                    	@foreach($permChunk as $permission)
	                        	<div class="col-md-4">
	                        		<label class="checkbox" style="margin-left: 40px; margin-top: 5px; font-weight: 600; font-size: 15px;"> <input type="checkbox" name="permissions[]" value="{{$permission->id}}"> {{$permission->label }}</label>
	                        	</div>
	                        @endforeach
	                    </div>
	                    @endforeach
				    </div>

				</div>


				<hr>
				@if(Gate::check('tenant.role.create') || Gate::check('tenant.admin.create'))
					<div class="text-right">
						<button type="submit" class="btn btn-primary">Create Role</button>
					</div>
				@else
					<p class="f-s-15 text-danger"> You do not have the permission to perform this action. Please Contact you Administrator.</p>

				@endif
			</form>

		</div>
	</div>
@endsection

@section('extra-script')
	<script type="text/javascript">
		
        $mn_list = $('.sidebar ul.nav > li.nav-account');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-roles').addClass('active');


	</script>
@endsection