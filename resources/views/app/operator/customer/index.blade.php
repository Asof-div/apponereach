@extends('layouts.operator_sidebar')

@section('title')

    CUSTOMER MANAGEMENT

@endsection

@section('breadcrumb')

    <li ><a href="{{ route('operator.dashboard') }}"> Dashboard </a></li>
    <li class="active"> Customers </li>

@endsection

@section('content')


    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel ">
            <div class="panel-heading">
                <div class="panel-title pt-2">
                    <span class="h3"> &nbsp; Customer List ({{ $customers->count() }})
                    </span>
                </div>
                <span class="pull-right mr-2">
                    {{-- <a href="{{ route('operator.customer.create') }}" class="btn btn-success"> <i class="fa fa-plus-circle"></i> New Customer </a> --}}

                </span>

            </div>

            <div class="panel-body" style="min-height: 400px;">

                <hr class="horizontal-line" />

                <div class="row">

                    @include('app.operator.customer.partials.table')

                </div>

            </div>



        </div>
    </div>


@endsection


@section('extra-script')

    <script>

        $mn_list = $('.page-sidebar-inner ul.sidebar-nav > li.customer');
        $mn_list.addClass('active open');
        $mn_list.find('.sub-menu > .customer-index').addClass('active');


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