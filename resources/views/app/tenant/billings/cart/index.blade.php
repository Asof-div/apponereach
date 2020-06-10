@extends('layouts.tenant_sidebar')

@section('title')
    
    CART MANAGEMENT

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.billing.index', [$tenant->domain]) }}"> Billing </a></li>
    <li class="active"> My Cart </li>

@endsection

@section('content')


    <div class="col-md-8 col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading"> 
                <div class="panel-title p-t-20">
                    <span class="h3"> Add Product To Your Cart </span>                   

                    <div class="panel-heading-btn m-r-10 m-t-10">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand" data-original-title="" title=""><i class="fa fa-expand"></i></a>
                    </div>
                </div>
                <hr class="m-t-5 m-b-0 horizonal-line-thick" />
            </div>

            <div class="panel-body" style="min-height: 400px;">


                <div class="col-md-12 col-sm-12 col-xs-12 p-t-15 bg-silver">
                    $tenant
                    @include('app.tenant.billings.cart.partials.product_list')
                
                </div>

            </div>

        </div>
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading"> 
                <div class="panel-title p-t-20">
                    <span class="h3"> Cart </span>                   

                    <div class="panel-heading-btn m-r-10 m-t-10">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand" data-original-title="" title=""><i class="fa fa-expand"></i></a>
                    </div>
                </div>
                <hr class="m-t-5 m-b-0" style="background-color: #51BB8D; height: 3px;" />
            </div>

            <div class="panel-body" style="min-height: 400px;">


                <div class="col-md-12 col-sm-12 col-xs-12 p-t-15 bg-silver">

                    {{-- @include('app.tenant.billings.cart.partials.product_list') --}}

                </div>

            </div>

        </div>
    </div>

@endsection


@section('extra-script')
    
    <script type="text/javascript">
        
        $mn_list = $('.sidebar ul.nav > li.nav-billing');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-cart').addClass('active');

    </script>


@endsection

@section('extra-css')
    <link href="{{ asset('assets/plugins/pricing-tables/css/style.css') }}" rel="stylesheet" type="text/css"/> 

    <style>
       
        li.check::before {
            font-family: "FontAwesome";
            content: "\f00c";
            font-size: 1.3rem;
            color: #33c4b6;
            margin-right: 3px;
        }
        li.uncheck::before {
            font-family: "FontAwesome";
            content: "\f00d";
            font-size: 1.3rem;
            color: red;
            margin-right: 3px;
        }
        label.full-display{
            display: block;
        }

    </style>

@endsection