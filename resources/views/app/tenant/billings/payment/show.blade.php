@extends('layouts.tenant_sidebar')

@section('title')
    
    TRANSACTION SUMMARY

@endsection

@section('breadcrumb')

    <li ><a href="{{ route('tenant.billing.index', [$tenant->domain]) }}"> Billing Summary </a></li>
    <li ><a href="{{ route('tenant.billing.transaction.index', [$tenant->domain]) }}"> Transaction </a></li>
    <li class="active"> Details </li>

@endsection

@section('content')

    <div class="col-md-12 col-sm-12 col-xs-12 p-0">
        <div class="panel">
            <div class="panel-body" style="min-height: 400px;">
                
                <hr class="horizonal-line-thick" />
                <div class="col-md-12 col-sm-12 col-xs-12 ">

                    @include('app.tenant.billings.payment.partials.details')

                </div>

            </div>



        </div>
    </div>


@endsection


@section('extra-script')    

    <script>
        $mn_list = $('.sidebar ul.nav > li.nav-billing');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-transaction').addClass('active');        

    </script>


@endsection

@section('extra-css')

    <style>
       

    </style>

@endsection