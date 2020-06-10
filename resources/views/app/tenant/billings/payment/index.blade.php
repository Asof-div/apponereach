@extends('layouts.tenant_sidebar')

@section('title')
    
    PAYMENT TRANSACTIONS ({{ $payments->total() }})

@endsection

@section('breadcrumb')

    <li ><a href="{{ route('tenant.billing.index', [$tenant->domain]) }}"> Billing Summary </a></li>
    <li class="active"> Payment </li>

@endsection

@section('content')

    <div class="col-md-12 col-sm-12 col-xs-12 p-0">
        <div class="panel">
            <div class="panel-body" style="min-height: 400px;">
                
                <div class="col-md-12 no-p m-b-10">
                    <form action="{{ route('tenant.billing.transaction.index', [$tenant->domain]) }}" method="get">
                       

                        <div class="col-md-2 clearfix">
                            <div class="input-group m-b-5">
                                <span class="input-group-addon" id="status">Status</span>
                                <select name="status" class="form-control" aria-describedby="status">
                                    <option {{ strtolower(request()->status) == 'all' ? 'selected' : '' }} value="all">All</option>
                                    <option {{ strtolower(request()->status) == 'success' ? 'selected' : '' }} value="success">Success</option>
                                    <option {{ strtolower(request()->status) == 'processing' ? 'selected' : '' }} value="processing">Processing</option>
                                    <option {{ strtolower(request()->status) == 'pending' ? 'selected' : '' }} value="pending">Pending</option>
                                    <option {{ strtolower(request()->status) == 'cancel' ? 'selected' : '' }} value="cancel">Cancel</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 clearfix">
                            <div class="input-group m-b-5">
                                <span class="input-group-addon" id="start_date">Start Date</span>
                                <input type="text" name="start_date" value="{{ request()->start_date }}" class="form-control datepicker" aria-describedby="start_date">
                                
                            </div>
                        </div>

                        <div class="col-md-4 clearfix">
                            <div class="input-group m-b-5">
                                <span class="input-group-addon" id="start_date">End Date</span>
                                <input type="text" name="end_date" value="{{ request()->end_date }}" class="form-control datepicker" aria-describedby="end_date">
                                
                            </div>
                        </div>

                        <div class="col-md-2 clearfix">
                            <div class="form-group m-b-5">
                                <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Search </button>                                
                            </div>
                        </div>
                        


                    </form>
                </div>
                
                <hr class="horizonal-line-thick" />
                <div class="col-md-12 col-sm-12 col-xs-12 ">

                    @include('app.tenant.billings.payment.partials.table')

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