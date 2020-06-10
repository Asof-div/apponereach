
<!-- begin sidebar scrollbar -->
<div data-scrollbar="true" data-height="100%">
    <!-- begin sidebar user -->
    <ul class="nav">
        <li class="nav-profile">
            <div class="image">
                <a href="javascript:;"><img src="{{ asset(Auth::user()->avatar) }}" alt="" /></a>
            </div>
            <div class="info">
                {{ Auth::user()->name }}
                <small>{{ Auth::user()->profile->role }}</small>
            </div>
        </li>
    </ul>
    <!-- end sidebar user -->
    <!-- begin sidebar nav -->
    <ul class="nav">
        <li class="nav-header text-white">Navigation</li>
        {{-- <li class="nav-dashboard ">
            <a href="{{route('tenant.dashboard', [$tenant->domain])}}">
                <i class="fa fa-laptop"></i>
                <span>Dashboard</span>
            </a>
        </li> --}}
        <li class="nav-edit-profile">
            <a href="{{ route('tenant.account.profile', [$tenant->domain])}}">
                <i class="fa fa-user"></i>
                <span class="f-s-14"> Profile </span>
            </a>
        </li>
        @if(Gate::check('tenant.match'))
            <li class="has-sub nav-billing">
                <a href="javascript:;">
                    <b class="caret pull-right"></b>
                    <i class="fa fa-credit-card"></i>
                    <span>Billing </span>
                </a>
                <ul class="sub-menu">
                    <li class="sub-menu-billing"><a href="{{route('tenant.billing.index', [$tenant->domain])}}">Billing Summary</a></li>

                    <li class="sub-menu-subscription"><a href="{{route('tenant.billing.subscription.index', [$tenant->domain])}}">Subscriptions </a></li>
                    <li></li>

                    @if(Gate::check('tenant.expiring'))
                        <li class="sub-menu-expiring"><a href="{{ route('tenant.billing.subscription.expiring', [$tenant->domain]) }}">Expiring/Expired</a></li>
                    @endif

                    <li class="sub-menu-transaction"><a href="{{ route('tenant.billing.transaction.index', [$tenant->domain]) }}"> Transactions </a></li>
                    
                </ul>
            </li>

            @if(Gate::check('tenant.subscription'))
                <li class="has-sub nav-account">
                    <a href="javascript:;">
                        <b class="caret pull-right"></b>
                        <i class="fa fa-users"></i>
                        <span>Account Management </span>
                    </a>
                    <ul class="sub-menu">
                        @if(Gate::check('tenant.user.access') || Gate::check('tenant.user.read'))
                        <li class="sub-menu-users"><a href="{{route('tenant.account.user.index', [$tenant->domain])}}">Users</a></li>
                        @endif
                        @if(Gate::check('tenant.role.access') || Gate::check('tenant.role.read'))
                        <li class="sub-menu-roles"><a href="{{route('tenant.account.role.index', [$tenant->domain])}}">Roles</a></li>
                        @endif
                        <li class="sub-menu-domain"><a href="{{route('tenant.account.domain.index', [$tenant->domain])}}">Settings</a></li>
                    </ul>
                </li>


                <li class="has-sub nav-media-services">
                    <a href="javascript:;">
                        <b class="caret pull-right"></b>
                        <i class="fa fa-suitcase"></i>
                        <span> Media Services </span>
                    </a>
                    <ul class="sub-menu">
                        @if(Gate::check('pilot_line'))
                        <li class="sub-menu-summary"><a href="{{ route('tenant.media-service.index', [$tenant->domain])}}"> <span class="f-s-13">Call Summary </span> </a></li>
                        @endif
                        @if(Gate::check('pilot_line'))
                        <li class="sub-menu-pilot-line"><a href="{{ route('tenant.media-service.pilot-line.index', [$tenant->domain])}}"> <span class="f-s-13"> Pilot Number  </span> </a></li>
                        @endif
                        @if(Gate::check('automated_call_routing'))
                        <li class="sub-menu-call-flow"><a href="{{route('tenant.media-service.call-flow.index', [$tenant->domain])}}"> <span class="f-s-13"> Call Flow </a></li>
                        @endif
                        @if(Gate::check('automated_call_routing'))
                        <li class="sub-menu-timer"><a href="{{route('tenant.media-service.timer.index', [$tenant->domain])}}"> <span class="f-s-13"> Time Scheduler </a></li>
                        @endif
                        @if(Gate::check('intercom'))
                        <li class="sub-menu-number"><a href="{{ route('tenant.media-service.number.index', [$tenant->domain]) }}"> Number </a></li>
                        @endif
                        @if(Gate::check('extension'))
                        <li class="sub-menu-extension"><a href="{{ route('tenant.media-service.exten.index', [$tenant->domain] ) }}"> <span class="f-s-14"> Extension </span> </a></li>
                        @endif
                        @if(Gate::check('group_call'))
                        <li class="sub-menu-group-ring"><a href="{{ route('tenant.media-service.group-ring.index', [$tenant->domain]) }}"> <span class="f-s-14"> Group Ring </span> </a></li>
                        @endif
                        @if(Gate::check('automated_company_greeting'))
                        <li class="sub-menu-sound"><a href="{{ route('tenant.media-service.sound.index', [$tenant->domain]) }}"> Play Media/Sound </a></li>
                        @endif
                        @if(Gate::check('automated_company_greeting'))
                        <li class="sub-menu-tts"><a href="{{ route('tenant.media-service.tts.index', [$tenant->domain]) }}"> Play Media/TTS </a></li>
                        @endif
                        @if(Gate::check('extension'))
                        <li class="sub-menu-gallery"><a href="{{ route('tenant.media-service.gallery.index', [$tenant->domain]) }}"> Gallery </a></li>
                        @endif
                        @if(Gate::check('ivr'))
                        <li class="sub-menu-virtual-receptionist"><a href="{{ route('tenant.media-service.virtual-receptionist.index', [$tenant->domain]) }}"> Virtual Receptionist </a></li>
                        @endif
                        
                    </ul>
                </li>
                
                <li class="has-sub nav-conference">
                    <a href="javascript:;">
                        <b class="caret pull-right"></b>
                        <i class="fa fa-sitemap"></i>
                        <span>Conferencing</span>
                    </a>
                    <ul class="sub-menu">
                        @if(Gate::check('local_conference') && ( Gate::check('tenant.conference_audio.access') || Gate::check('tenant.conference_audio.read') ) )
                            
                            <li class="sub-menu-audio-conf"><a href="{{ route('tenant.conference.audio.index', [$tenant->domain] ) }}"> <span class="f-s-14"> Audio Conference </span> </a></li>
                        @endif

                        {{-- @if(Gate::check('public_meeting_room'))
                            <li class="sub-menu-public-conf"><a href="{{ route('tenant.conference.private.index', [$tenant->domain] ) }}"> <span class="f-s-14"> Public Conference </span> </a></li>
                        @endif --}}
                        {{-- @if(Gate::check('private_meeting_room'))
                            <li class="sub-menu-private-conf"><a href="{{ route('tenant.conference.private.index', [$tenant->domain] ) }}"> <span class="f-s-14"> Private Conference </span> </a></li>
                        @endif --}}

                    </ul>
                </li>

                @if(Gate::check('crm'))                
                <li class="has-sub nav-crm">
                    <a href="javascript:;">
                        <b class="caret pull-right"></b>
                        <i class="fa fa-laptop"></i>
                        <span>CRM</span>
                    </a>
                    <ul class="sub-menu">
                        <li class="sub-menu-account"><a href="{{ route('tenant.crm.account.index', [$tenant->domain]) }}">Account</a></li>
                        <li class="sub-menu-account-new"><a href="{{ route('tenant.crm.account.create', [$tenant->domain]) }}">New Account</a></li>
                        <li class="sub-menu-contact"><a href="{{ route('tenant.crm.contact.index', [$tenant->domain]) }}">Contact</a></li>
                        {{-- <li class="sub-menu-opportunity"><a href="{{ route('tenant.crm.opportunity.index', [$tenant->domain]) }}">Opportunities</a></li> --}}
                        <li class="sub-menu-quote"><a href="{{ route('tenant.crm.quote.index', [$tenant->domain]) }}">Quotes</a></li>
                        <li class="sub-menu-invoice"><a href="{{ route('tenant.crm.invoice.index', [$tenant->domain]) }}">Invoices</a></li>
                        <li class="sub-menu-task"><a href="{{ route('tenant.crm.task.index', [$tenant->domain]) }}">Task</a></li>
                    </ul>

                </li>
                @endif
                @if(Gate::check('ticketing_support') && (Gate::check('tenant.ticket.access') || Gate::check('tenant.ticket.read')) )                
                <li class="nav-ticket">
                    <a href="{{ route('tenant.ticket.index', [$tenant->domain])}}">
                        <i class="fa fa-ticket"></i>
                        <span class="f-s-14"> Ticket </span>
                    </a>
                </li>
                @endif
                @if(Gate::check('instant_chat') )                
                {{-- <li class="nav-chat">
                    <a href="{{ route('tenant.chat.index', [$tenant->domain])}}">
                        <i class="fa fa-wechat"></i>
                        <span class="f-s-14"> Chat </span>
                    </a>
                </li> --}}
                @endif
            @endif
        @endif
        <li>
            <a href="{{ route('tenant.logout', [$tenant->domain]) }}"
                onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">
                <i class="fa fa-power-off"></i>
                <span class="f-s-15"> Logout </span>
            </a>

            <form id="logout-form" action="{{ route('tenant.logout', [$tenant->domain]) }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </li>

        <!-- begin sidebar minify button -->
        <li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
        <!-- end sidebar minify button -->
    </ul>
    <!-- end sidebar nav -->
    <ul class="nav width-full" style="position: absolute; bottom: 0px;">
        <li class="nav-profile">
            
            <a><img width="50" src="{{asset('images/9Mobile.png')}}"> <span class="f-s-20 f-w-700"> Cloud PBX </span> </a>
        
        </li>
    </ul>
</div>
<!-- end sidebar scrollbar -->
