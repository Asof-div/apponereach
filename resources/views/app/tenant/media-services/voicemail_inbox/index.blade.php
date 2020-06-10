@extends('layouts.tenant_sidebar')

@section('title')
    
    VOICEMAIL BOX

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.media-service.index', [$tenant->domain]) }}"> Media Services </a></li>
    <li class="active"> Voicemail Box </li>

@endsection

@section('content')

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading"> 
                <div class="panel-title p-b-10 p-t-10">
                    <span class="h3"> 
                        Voicemail Box List ({{ $inboxes->count() }})
                    </span> 
                    <span class="pull-right m-r-10">
                        <a href="{{ route('tenant.media-service.inbox.create', [$tenant->domain]) }}" class="btn btn-lg btn-outline-default"> New Voicemail Box </a>
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

                    @include('app.tenant.media-services.voicemail_inbox.partials.table')

                </div>

            </div>



        </div>
    </div>

    <div class="modal fade delete-call-flow-modal" tabindex="1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <form method="post" action="{{ route('tenant.media-service.call-flow.delete', [$tenant->domain]) }}">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span> </button>
                        <h5 class="modal-title"> <span class="h4 text-primary"> DELETE CALL FLOW </span> </h5>
                    </div>

                    <div class="modal-body">
                     
                        {{ csrf_field() }}
                        <p class="f-s-15"> Are you sure you want to delete this ? </p>
                        <input type="hidden" name="route_id" value="">
                         
                    </div>

                    <div class="modal-footer">
                        <div class="form-inline">
                            <div class="form-group m-r-10">
                                <button type="button" class="btn btn-warning" data-dismiss="modal"> NO </button>
                                <button type="submit" class="btn btn-primary"> YES </button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>            
        </div>
    </div>
        
@endsection


@section('extra-script')

    <script type="text/javascript">
        $mn_list = $('.sidebar ul.nav > li.nav-media-services');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-voicemail').addClass('active');

    </script>


@endsection


@section('extra-css')

    <link type="text/css" rel="stylesheet" href="{{ URL::to('css/rSlider.css') }}" />

    <style type="text/css">
        
        .width-350{
            width: 350px !important;
        }
        .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
        }

        .switch input {display:none;}

        .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
        }

        .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
        }

        input:checked + .slider {
        background-color: #2196F3;
        }

        input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
        border-radius: 34px;
        }

        .slider.round:before {
        border-radius: 50%;
        }

        .day_of_week:nth-child(even){

            background : #f2f2d2;
            padding: 10px;
            padding-left: 30px;
            margin: 10px 10px 10px 0;
            width: 70px; 
        }
        .day_of_week:nth-child(odd){

            background : #e2e2e2;
            padding: 10px;
            padding-left: 30px;
            margin: 10px 10px 10px 0;
            width: 70px; 
        }

        .day_of_week_checked{

            background: #51bb8d !important;

        }
        
        .rs-scale{
            width: 110%;
        }

        .rs-container{
            width: 95% !important;
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