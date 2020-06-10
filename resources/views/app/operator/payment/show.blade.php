@extends('layouts.operator_sidebar')

@section('title')
    
    TRANSACTION SUMMARY

@endsection

@section('breadcrumb')

    <li ><a href="{{ route('operator.dashboard') }}"> Dashboard </a></li>
    <li><a href="{{ route('operator.customer.index') }}"> Customer </a></li>
    <li><a href="{{ route('operator.customer.transaction.index') }}"> Payment </a></li>
    <li class="active"> Details </li>

@endsection

@section('content')

    <div class="col-md-12 col-sm-12 col-xs-12 p-0">
        <div class="panel">
            <div class="panel-body" style="min-height: 400px;">
                
                <hr class="horizonal-line-thick" />
                <div class="col-md-12 col-sm-12 col-xs-12 ">

                    @include('app.operator.payment.partials.details')

                </div>

            </div>



        </div>
    </div>


    <div class="modal fade" id="payment_confirmation_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('operator.customer.transaction.status') }}" method="POST">
                    {{ csrf_field() }}

                    <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                    <input type="hidden" name="status" value="success">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"> Confirm Payment </h4>
                    </div>
                    <div class="modal-body">
                        <div class="f-s-15">
                            Kindly, confirm you want to payment.
                        </div>
                        <div class="form-group m-t-20 f-s-15">
                            <label>Paid Date <i class="fa fa-asterisk text-danger"></i></label>
                            <input type="text" name="paid_date" class="form-control datepicker">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success"> <i class="fa fa-exchange"></i> Confirm Payment </button>
                    </div>

                </form>
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