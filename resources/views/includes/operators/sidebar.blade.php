<div class="page-sidebar-inner slimscroll">
    <div class="sidebar-header">
        <div class="sidebar-profile">
            <a href="javascript:void(0);" id="profile-menu-link">
                <div class="sidebar-profile-image">
                    <img src="{{ asset(Auth::user()->avatar) }}" class="img-circle img-responsive" alt="Profile Picture">
                </div>
                <div class="sidebar-profile-details">
                    <span>{{ Auth::user()->name }}<br><small>{{ Auth::user()->job_title }}</small></span>
                </div>
            </a>
        </div>
    </div>
    <ul class="menu sidebar-nav accordion-menu">

        <li class="dashboard"><a href="{{ route('operator.dashboard') }}" class="waves-effect waves-button"><span class="menu-icon glyphicon glyphicon-home"></span><p>Dashboard</p></a></li>

        <li class="menu-profile"><a href="{{ route('operator.profile.index') }}" class="waves-effect waves-button"><span class="menu-icon glyphicon glyphicon-user"></span><p>Profile</p></a></li>

        <li class="droplink user">
            <a href="#" class="waves-effect waves-button">
                <span class="fa fa-users fa-2x"></span><p> User MGT </p><span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="user-index"><a href="{{ route('operator.user.index') }}">User List</a></li>
                <li class="user-new"><a href="{{ route('operator.user.create') }}">New User</a></li>
                <li class="user-role"><a href="{{ route('operator.role.index') }}">Roles </a></li>
            </ul>
        </li>

        <li class="droplink pilot_number">
            <a href="#" class="waves-effect waves-button">
                <span class="menu-icon glyphicon glyphicon-phone"></span><p>Pilot Number</p><span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="pilot_number-index"><a href="{{ route('operator.pilot_number.index') }}">Number List</a></li>
                <li class="pilot_number-new"><a href="{{ route('operator.pilot_number.create') }}">New Number</a></li>

            </ul>
        </li>

        <li class="droplink customer">
            <a href="#" class="waves-effect waves-button">
                <span class="menu-icon glyphicon glyphicon-briefcase "></span><p>Customers</p><span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="customer-index"><a href="{{ route('operator.customer.index') }}">Customer</a></li>

            </ul>
        </li>

        <li class="droplink billing">
            <a href="#" class="waves-effect waves-button">
                <span class="menu-icon glyphicon glyphicon-usd "></span><p>Billing</p><span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="subscription-index"><a href="{{ route('operator.subscription.index') }}">Subscription</a></li>
                <li class="order-index"><a href="{{ route('operator.customer.order.index') }}">Order</a></li>
                <li class="transaction-index"><a href="{{ route('operator.customer.transaction.index') }}">Transaction</a></li>

            </ul>
        </li>

        <li class="droplink calls">
            <a href="#" class="waves-effect waves-button">
                <span class="menu-icon glyphicon glyphicon-earphone "></span><p>Calls</p><span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="rate-index"><a href="{{ route('operator.call.rate.index') }}">Call Rate</a></li>
                <li class="history-index"><a href="{{ route('operator.call.history.index') }}">Call History </a></li>
                <li class="airtime-index"><a href="{{ route('operator.customer.transaction.index') }}">Airtime Usage</a></li>

            </ul>
        </li>

        <li class="droplink ticket">
            <a href="#" class="waves-effect waves-button">
                <span class="menu-icon glyphicon glyphicon-envelope"></span><p>Ticket MGT</p><span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="incident-index"><a href="{{ route('operator.incident.index') }}">Incidents</a></li>
                <li class="ticket-index"><a href="{{ route('operator.ticket.index') }}">Tickets</a></li>
                <li class="ticket-unassigned"><a href="{{ route('operator.ticket.unassigned') }}">Unassigned Tickets</a></li>
                <li class="ticket-new"><a href="{{ route('operator.ticket.create') }}">New Ticket</a></li>
            </ul>
        </li>


        <li><a class="waves-effect waves-button" href="{{ route('operator.logout') }}"
                onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();"><span class="fa fa-sign-out fa-2x"></span><p>Log out </p></a>
            <form id="logout-form" action="{{ route('operator.logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </li>

    </ul>
</div><!-- Page Sidebar Inner -->