@extends('layouts.tenant_sidebar')

@section('title')
    
    CONFERENCE MANAGEMENT

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li class="active"> Audio Conference </li>

@endsection

@section('content')

   
    <div class="col-md-12 col-sm-12 col-xs-12 p-0">
        <div class="panel panel-default">
            <div class="panel-heading"> 
            
                <div class="panel-title m-t-10"> 
                    <span class="h3"> Conference &nbsp; <span class="text-primary"> ({{ $conferences->count() }}) </span> </span> 
                    <span class="pull-right m-r-10">
                        <a href="{{ route('tenant.conference.audio.create', [$tenant->domain]) }}" class="btn btn-lg btn-outline-default"> <i class="fa fa-plus-circle"></i> Add Audio Conference </a>
                    </span>

                    <div class="panel-heading-btn m-r-10 m-t-10">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand" data-original-title="" title=""><i class="fa fa-expand"></i></a>
                    </div>
                </div> 
                <hr class="horizonal-line-thick">

            </div>

            <div class="panel-body" style="min-height: 400px;">
                @if(!Gate::check('private_meeting_room'))
                <div class="col-md-12">
                    <span class="h4 text-danger">Note !!! The maximum number of conference room for this package is 3</span>
                </div>
                @endif
                <div class="col-md-12 col-sm-12 col-xs-12" >

                    @include('app.tenant.conference.audio_conference.partials.table')

                </div>

            </div>
        </div>
    </div>

        



@endsection


@section('extra-script')

    <script>
       
        $mn_list = $('.sidebar ul.nav > li.nav-conference');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-audio-conf').addClass('active');



    
    </script>


@endsection

@section('extra-css')
        
    <style>


    </style>

@endsection