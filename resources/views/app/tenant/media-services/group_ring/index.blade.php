@extends('layouts.tenant_sidebar')

@section('title')
    
    GROUP RING 

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.media-service.index', [$tenant->domain]) }}"> Media Services </a></li>
    <li class="active"> Group Ring </li>

@endsection

@section('content')

   
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading"> 
            
                <div class="panel-title p-t-10"> 
                    <span class="h3"> Total Group Rings <span class="text-primary"> ({{ $group_rings->count() }}) </span> </span> 
                    <span class="pull-right m-r-10">
                        <a href="{{ route('tenant.media-service.group-ring.create', [$tenant->domain]) }}" class="btn btn-lg btn-outline-default"> <i class="fa fa-plus-circle"></i> Add Group Ring </a>
                    </span>
                </div> 
                <hr class="horizonal-line-thick">
            </div>

            <div class="panel-body" style="min-height: 400px;">

                <div class="col-md-12 col-sm-12 col-xs-12">

                    @include('app.tenant.media-services.group_ring.partials.table')

                </div>

            </div>
        </div>
    </div>

        



@endsection


@section('extra-script')

    <script>
        
        $mn_list = $('.sidebar ul.nav > li.nav-media-services');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-group-ring').addClass('active');

        $(function(){

            $(".phone-route>span").mask("(000) 9999-9999");

        });

    </script>


@endsection

@section('extra-css')
        
    <style>

       .control-label{
            text-align : right;
            padding: 15px 15px 15px 25px;
        }

        .phone-route{
            padding: 7px;
            margin: 7px;
            border-radius: 4px;
            background: #f3f3f4;
            display: inline-block;
        }

    </style>

@endsection