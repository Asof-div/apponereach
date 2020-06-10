@extends('layouts.tenant_sidebar')

@section('title')
	Roles 
@endsection

@section('breadcrumb')

  	<li class="active">Roles</li>

@endsection

@section('content')

	<div class="panel ">
	    <div class="panel-heading"> 
            <div class="panel-title">
                <span class="h3"> &nbsp; Roles ({{ $roles->total() }}) </span> 
            
	            <span class="pull-right m-r-10">
	                @if(Gate::check('tenant.role.create'))
		                <a href="{{ route('tenant.account.role.create', $tenant->domain) }}" class="btn btn-outline-default"> <i class="fa fa-plus"></i> Add Role <span class="text-primary"> </span> </a>
	                @endif
	            </span>
          	</div>
          	<hr class="horizonal-line-thick">  
        </div>

		<div class="panel-body">
          
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
									@if(Gate::check('tenant.role.read'))
										<a href="{{ route('tenant.account.role.show', [$tenant->domain, $role->id]) }}"> <i class="fa fa-eye"></i> </a>
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
		
        $mn_list = $('.sidebar ul.nav > li.nav-account');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-roles').addClass('active');

	</script>
@endsection

@section('extra-css')
	<style type="text/css">
		a.btn{display: inline-block;}
		
	</style>
@endsection