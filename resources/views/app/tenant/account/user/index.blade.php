@extends('layouts.tenant_sidebar')

@section('title')
    
    USER MANAGEMENT

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li class="active"> Users </li>

@endsection

@section('content')


    <div class="col-md-12 col-sm-12 col-xs-12 p-0">
        <div class="panel panel-default">
            <div class="panel-heading"> 
                <div class="panel-title p-t-20">
                    <span class="h3"> &nbsp; User List ({{ $users->count() }}) 
                    </span> 
                    <span class="pull-right m-r-10">
                        <a href="{{ route('tenant.account.user.create', [$tenant->domain]) }}" class="btn btn-lg btn-outline-default"> <i class="fa fa-plus-circle"></i> New User &nbsp; <span class="text-primary"> </span> </a>
                    </span>
                    

                    <div class="panel-heading-btn m-r-10 m-t-10">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand" data-original-title="" title=""><i class="fa fa-expand"></i></a>
                    </div>
                </div>
            </div>

            <div class="panel-body" style="min-height: 400px;">

                <hr style="background-color: #51BB8D; height: 3px;" />

                <div class="col-md-12 col-sm-12 col-xs-12 p-t-15 p-b-15 bg-silver">

                    @include('app.tenant.account.user.partials.table')

                </div>

            </div>



        </div>
    </div>


@endsection


@section('extra-script')
    
    <script type="text/javascript">
        $mn_list = $('.sidebar ul.nav > li.nav-account');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-users').addClass('active');
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