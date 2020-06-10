@extends('layouts.operator_sidebar')

@section('title')
	Create Operator Role
@endsection

@section('breadcrumb')
    <li><a href="{{ route('operator.dashboard') }}">Dashboard</a></li>
    <li><a href="{{ route('operator.role.index') }}"> Roles</a></li>
    <li class="active">Create</li>
@endsection

@section('content')

    <div class="panel ">
    	<div class="panel-heading"> 
            <div class="panel-title">
                <span class="h3"> &nbsp; Add New Role  </span> 
            </div>
            <span class="pull-right m-r-10">
                <a href="{{ route('operator.role.index') }}" class="btn btn-lg btn-outline-default"> <i class="fa fa-list"></i> Roles <span class="text-primary"> </span> </a>
            </span>
            
        </div>

		<div class="panel-body">
            <hr style="background-color: #51BB8D; height: 3px;" />

			<form name="frmAddAdminRole" method="POST" action="{{ route('operator.role.store') }}">
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

			    <div class="p-20 col-md-12 bg-white m-b-15">
				    <div class="clearfix m-b-15 m-l-15"> <span class="h4 text-primary"> Permissions </span> </div>
				    <span></span>


				    <div class="col-md-12 col-sm-12">


				        @foreach($permissions->chunk(3) as $permChunk)
	                    <div class="row">
	                    	@foreach($permChunk as $permission)
	                        	<div class="col-md-4">
	                        		<label data-toggle="tooltip" data-placement="top" title="{{$permission->description}} " class="checkbox" style="margin-left: 40px; margin-top: 5px; font-weight: 600; font-size: 15px;"> <input type="checkbox" name="permissions[]" value="{{$permission->id}}"> {{$permission->label }}</label>
	                        	</div>
	                        @endforeach
	                    </div>
	                    @endforeach
				    </div>

				</div>


				<hr>
				@if(Gate::check('role.create') )
					<div class="text-right">
						<button type="submit" class="btn btn-primary">Create Role</button>
					</div>
				@else
				    <div class="col-md-12 bg-danger clearfix m-t-30 p-15 m-r-0 m-l-0">
				        <p class="f-s-16 p-10"> You do not have the permission to perform this action. Please Contact you Administrator.</p>
				    </div>
				@endif

			</form>

		</div>
	</div>
@endsection

@section('extra-script')
	<script type="text/javascript">
		
        $mn_list = $('.page-sidebar-inner ul.sidebar-nav > li.user');
        $mn_list.addClass('active open');
        $mn_list.find('.sub-menu > .user-role').addClass('active');

	</script>
@endsection