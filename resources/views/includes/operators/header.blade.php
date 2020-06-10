<div class="navbar-inner">
    <div class="sidebar-pusher">
        <a href="javascript:void(0);" class="waves-effect waves-button waves-classic push-sidebar">
            <i class="fa fa-bars"></i>
        </a>
    </div>
    <div class="logo-box">
        <a href="{{ route('pbx.landing') }}" class="logo-text"> <span><img src="{{ asset('images/logo.jpeg') }}" height="55px" alt="UC PBX" /></span></a>
    </div><!-- Logo Box -->
    <div class="search-button">
        <a href="javascript:void(0);" class="waves-effect waves-button waves-classic show-search"><i class="fa fa-search"></i></a>
    </div>
    <div class="topmenu-outer">
        <div class="top-menu">
            @guest('operator')

            @else

                <ul class="nav navbar-nav navbar-left">

                    <li>        
                        <a href="javascript:void(0);" class="waves-effect waves-button waves-classic toggle-fullscreen"><i class="fa fa-expand"></i></a>
                    </li>

                </ul>
            @endguest
            <ul class="nav navbar-nav navbar-right">
                @guest('operator')
                    
                    <li><a href="{{ route('operator.login') }}" class="waves-effect waves-button waves-classic">Login</a></li>

                @else



                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle waves-effect waves-button waves-classic" data-toggle="dropdown">
                            <span class="user-name">{{ Auth::user()->firstname }}<i class="fa fa-angle-down"></i></span>
                            <img class="img-circle avatar" src="{{ asset(Auth::user()->avatar) }}" width="40" height="40" alt="{{ Auth::user()->name }}">
                        </a>
                        <ul class="dropdown-menu dropdown-list" role="menu">
                            
                            <li role="presentation"><a href="{{ route('operator.profile.index') }}"><i class="fa fa-user"></i> Profile</a></li>
                            <li role="presentation" class="divider"></li>
                            <li role="presentation">
                                <a href="{{ route('operator.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out m-r-xs"></i>
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ route('operator.logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>            
                            </li>

                        </ul>
                    </li>
                @endguest
            </ul><!-- Nav -->
        </div><!-- Top Menu -->
    </div>
</div>
