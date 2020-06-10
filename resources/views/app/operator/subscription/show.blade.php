@extends('layouts.operator_sidebar')

@section('title')
    
    CUSTOMER SUBSCRIPTION 

@endsection

@section('breadcrumb')

    <li ><a href="{{ route('operator.dashboard') }}"> Dashboard </a></li>
    <li ><a href="{{ route('operator.subscription.index') }}"> Subscriptions </a></li>
    <li class="active"> Details </li>

@endsection

@section('content')

    <div class="col-md-12 col-sm-12 col-xs-12 no-p">
        <div class="panel">
            <div class="panel-body" style="min-height: 400px;">
                
                <h3>{{ $subscription->tenant->name .' - '. $subscription->start_time->format('Y F') }}</h3>
                <hr class="horizonal-line-thick" />
                <div class="col-md-12 col-sm-12 col-xs-12 ">

                    @include('app.operator.subscription.partials.details')

                </div>

            </div>



        </div>
    </div>


@endsection


@section('extra-script')    

    <script>
       
        $mn_list = $('.page-sidebar-inner ul.sidebar-nav > li.billing');
        $mn_list.addClass('active open');
        $mn_list.find('.sub-menu > .subscription-index').addClass('active');

        

    </script>


@endsection

@section('extra-css')

    <style>
       

    </style>

@endsection