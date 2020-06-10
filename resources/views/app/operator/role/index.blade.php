@extends('layouts.operator_sidebar')

@section('title')
	Operator Roles 
@endsection

@section('breadcrumb')
	<li><a href="{{ route('operator.dashboard') }}">Dashboard</a></li>
  	<li class="active">Roles</li>
@endsection

@section('content')

	<div class="panel ">
	    <div class="panel-heading"> 
            <div class="panel-title">
                <span class="h3"> &nbsp; Roles ({{ $roles->total() }}) </span> 
            </div>
            <span class="pull-right m-r-10">
                @if(Gate::check('role.create'))
	                <a href="{{ route('operator.role.create') }}" class="btn btn-lg btn-outline-default"> <i class="fa fa-plus"></i> Add Role <span class="text-primary"> </span> </a>
                @endif
            </span>
            
        </div>

		<div class="panel-body">
            <hr style="background-color: #51BB8D; height: 3px;" />

			<div class="table-responsive">
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
						@forelse($roles as $role)
							<tr>
								<td>{{ $role->name }}</td>
								<td>{{ $role->label }}</td>
								<td>{{ $role->description }}</td>
								<td>
									@if(Gate::check('role.read'))
										<a href="{{ route('operator.role.show', [$role->id]) }}"> <i class="fa fa-eye"></i> </a>
									@endif
								</td>
							</tr>
						@empty
							<tr><td class="text-center" colspan="4"> No roles added</td></tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>
	</div>


	{{ $roles->links() }}

@endsection

@section('extra-script')
	<script type="text/javascript">
		
        $mn_list = $('.page-sidebar-inner ul.sidebar-nav > li.user');
        $mn_list.addClass('active open');
        $mn_list.find('.sub-menu > .user-role').addClass('active');

	</script>
@endsection