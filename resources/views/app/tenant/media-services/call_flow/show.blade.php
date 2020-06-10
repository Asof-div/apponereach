@extends('layouts.tenant_sidebar')

@section('title')
    
    CALL FLOW

@endsection

@section('breadcrumb')

    <li><a href="{{ url($tenant->domain.'/dashboard') }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.media-service.index', [$tenant->domain]) }}"> Media Services </a></li>
    <li><a href="{{ route('tenant.media-service.call-flow.index', [$tenant->domain]) }}"> Call Flow </a></li>
    <li class="active"> View Call Flow </li>

@endsection

@section('content')
    
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading"> 
                <div class="panel-title p-b-10 p-t-10">
                    <span class="h3"> 
                        Show Call Flow Details
                    </span> 
                    <span class="pull-right m-r-10">
                        <a href="{{ route('tenant.media-service.call-flow.index', [$tenant->domain]) }}" class="btn btn-lg btn-outline-default"> Call Flow </a>
                    </span>
                    

                    <div class="panel-heading-btn m-r-10 m-t-10">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand" data-original-title="" title="">
                            <i class="fa fa-expand"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="panel-body" style="min-height: 400px;">

                <hr style="background-color: #51BB8D; height: 3px;" />

                <div class="col-md-12 col-sm-12 col-xs-12 p-t-15 bg-silver">

                    {{-- @include('app.tenant.media-services.call_flow.partials.details') --}}

                </div>

            </div>



        </div>
    </div>
        
@endsection


@section('extra-script')
    
    <script type="text/javascript" src="{{ URL::to('js/rSlider.js') }}"></script>
    <script type="text/javascript">
        $mn_list = $('.sidebar ul.nav > li.nav-media-services');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-call-flow').addClass('active');


      </script>


@endsection


@section('extra-css')

    <link type="text/css" rel="stylesheet" href="{{ URL::to('css/rSlider.css') }}" />

    <style type="text/css">
        
        .call-flow-component{
            padding: 15px;
            padding-left: 40px;
            margin: 10px;
        }

        .phone-route{
            padding: 7px;
            margin: 7px;
            border-radius: 4px;
            background: #f3f3f4;
            display: inline-block;

        }
        .nav-stacked > li {
            border: 2px #51bb8d solid;
            border-radius: 4px;
            background-color: #f2f2f2;
        }

    </style>

@endsection