@extends('layouts.tenant_sidebar')

@section('title')
    
    ACCOUNT 

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.crm.index', [$tenant->domain]) }}"> CRM </a></li>
    <li class="active"> ACCOUNT </li>

@endsection

@section('content')

   
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading"> 
            
                <div class="panel-title"> 
                    <span class="h3"> Total Accounts &nbsp; <span class="text-primary"> {{ $accounts->count() }} </span> </span> 
                    <span class="pull-right m-r-10">
                        <a href="{{ route('tenant.crm.account.create', [$tenant->domain]) }}" class="btn btn-lg btn-outline-default"> <i class="fa fa-plus-circle"></i> Add Account </a>
                    </span>
                </div> 
                <hr class="horizonal-line-thick">

            </div>

            <div class="panel-body" style="min-height: 400px;">

                <div class="col-md-12 col-sm-12 col-xs-12" id="accounts_container">

                    @include('app.tenant.crm.account.partials.table')

                </div>

            </div>
        </div>
    </div>

        



@endsection


@section('extra-script')

    <script>
       
        $mn_list = $('.sidebar ul.nav > li.nav-crm');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-account').addClass('active');



    
    </script>


@endsection

@section('extra-css')
        
    <style>


    </style>

@endsection