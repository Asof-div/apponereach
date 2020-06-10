@extends('layouts.tenant_sidebar')

@section('title')
    
    VIRTUAL RECEPTIONIST

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.media-service.index', [$tenant->domain]) }}"> Media Services </a></li>
    <li class="active"> Virtual Receptionist </li>

@endsection

@section('content')

   
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading"> 
            
                <div class="panel-title p-t-10"> 
                    <span class="h3"> Virtual Receptionist List <span class="text-primary"> ({{ $receptionists->count() }}) </span> </span> 
                    <span class="pull-right m-r-10">
                        <a href="{{ route('tenant.media-service.virtual-receptionist.create', [$tenant->domain]) }}" class="btn btn-lg btn-outline-default" > <i class="fa fa-plus-circle"></i> Add Virtual Receptionist </a>
                    </span>
                </div> 
                <hr class="horizonal-line-thick">
            </div>

            <div class="panel-body" style="min-height: 400px;">

                <div class="col-md-12 col-sm-12 col-xs-12">

                    @include('app.tenant.media-services.virtual-receptionist.partials.table')

                </div>

            </div>
        </div>
    </div>




@endsection


@section('extra-script')

    <script>
       
        $mn_list = $('.sidebar ul.nav > li.nav-media-services');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-virtual-receptionist').addClass('active');


    </script>


@endsection

@section('extra-css')
        
    <style>

        


    </style>

@endsection