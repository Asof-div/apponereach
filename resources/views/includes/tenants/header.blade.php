<div class="container-fluid">
	<!-- begin mobile sidebar expand / collapse button -->
	<div class="navbar-header">
		<a href="{{ route('tenant.index', [$tenant->domain]) }}" class="navbar-nav">
            <img id="navbar_logo" class=" navbar-nav pull-left" src="{{asset($tenant->info->logo)}}" alt="" style="width: 50px; height: 50px; display:inline;">      
            <span class="navbar-brand"> Onereach </span>
        </a>
		<button type="button" class="navbar-toggle" data-click="sidebar-toggled">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
	</div>
	<!-- end mobile sidebar expand / collapse button -->
	
	<!-- begin header navigation right -->
	<ul class="nav navbar-nav navbar-right">
        @guest('web')
            <li><a href="{{ route('tenant.login', [$tenant->domain]) }}">Login</a></li>
        @else

    		<li class="dropdown navbar-user">

    			<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
    				<img src="{{ asset(Auth::user()->avatar) }}" alt="" /> 
    				<span class="hidden-xs">{{ Auth::user()->lastname." ".Auth::user()->firstname }}</span> <b class="caret"></b>
    			</a>
    			<ul class="dropdown-menu animated fadeInLeft">
    				<li class="arrow"></li>
    				<li><a href="{{ route('tenant.account.profile', [$tenant->domain ]) }}">Edit Profile</a></li>
    				<li class="divider"></li>
    				<li>
                        <a href="{{ route('tenant.logout', [$tenant->domain]) }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>

                        <form id="logout-form" action="{{ route('tenant.logout', [$tenant->domain]) }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>            
                    </li>
    			</ul>
    		</li>

        @endguest
	</ul>
	<!-- end header navigation right -->
</div>