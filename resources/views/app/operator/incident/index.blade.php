@extends('layouts.operator_sidebar')

@section('title')
    
    TICKET INCIDENT MANAGEMENT

@endsection

@section('breadcrumb')

    <li ><a href="{{ route('operator.dashboard') }}"> Dashboard </a></li>
    <li class="active"> Incidents </li>

@endsection

@section('content')


    <div class="col-md-12 col-sm-12 col-xs-12 no-p">
        <div class="panel ">
            <div class="panel-heading"> 
                <div class="panel-title pt-2">
                    <span class="h3"> &nbsp; Incidents ({{ $incidents->count() }}) 
                    </span>
                </div> 
                <span class="pull-right mr-2">
                    <a href="{{ route('operator.incident.create') }}" class="btn btn-success"> <i class="fa fa-plus-circle"></i> Add Incident &nbsp; <span class="text-primary"> </span> </a>
                </span>

            </div>

            <div class="panel-body" style="min-height: 400px;">

                <hr style="background-color: #51BB8D; height: 3px;" />

                <div class="col-md-12 col-sm-12 col-xs-12 p-t-15 p-b-15 bg-silver">

                    @include('app.operator.incident.partials.table')

                </div>

            </div>



        </div>
    </div>


@endsection


@section('extra-script')    

    <script>
       
        $mn_list = $('.page-sidebar-inner ul.sidebar-nav > li.ticket');
        $mn_list.addClass('active open');
        $mn_list.find('.sub-menu > .incident-index').addClass('active');


    </script>


@endsection

@section('extra-css')

    <style>
       
        ul.nav.nav-pills li.active a{
            background: #51BB8D !important;
            color: #fff !important;
            font-weight: bold;
        }

    </style>

@endsection