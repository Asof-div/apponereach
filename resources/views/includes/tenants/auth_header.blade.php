<div class="container-fluid">
	<!-- begin mobile sidebar expand / collapse button -->
	<div class="navbar-header">
		 <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" alt="9Mobile CloudPBX" href="{{ route('pbx.landing') }}"><img src="{{  asset('images/logo.png') }}" width="120px"></a>
	</div>
	<!-- end mobile sidebar expand / collapse button -->
	
	<!-- begin header navigation right -->
	<ul class="nav navbar-nav navbar-right">
        @guest('web')
            <li><a href="{{ route('login') }}">Login</a></li>
            {{-- <li><a href="{{ route('register') }}">Sign Up As Prepaid</a></li> --}}

        @else

        @endguest
	</ul>
	<!-- end header navigation right -->
</div>