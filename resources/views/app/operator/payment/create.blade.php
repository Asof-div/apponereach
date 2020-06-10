@extends('layouts.operator_sidebar')

@section('title')
    
    PAYMENT TRANSACTIONS 

@endsection

@section('breadcrumb')

    <li ><a href="{{ route('operator.dashboard') }}"> Dashboard </a></li>
    <li><a href="{{ route('operator.customer.index') }}"> Customer </a></li>
    <li><a href="{{ route('operator.customer.transaction.index') }}"> Payment </a></li>
    <li class="active"> New Payment </li>

@endsection

@section('content')

    <div class="col-md-12 col-sm-12 col-xs-12 p-0">
        <div class="panel">
            <div class="panel-body" style="min-height: 400px;">
                
                <div class="col-md-12 col-sm-12 col-xs-12 ">

                    @include('app.operator.payment.partials.form')

                </div>

            </div>



        </div>
    </div>


@endsection


@section('extra-script')    

    <script>
       
        $mn_list = $('.page-sidebar-inner ul.sidebar-nav > li.billing');
        $mn_list.addClass('active open');
        $mn_list.find('.sub-menu > .transaction-index').addClass('active');

    </script>


@endsection

@section('extra-css')

    <style>
       

    </style>

@endsection