@extends('layouts.operator_sidebar')

@section('title')
    
    CUSTOMER ORDER MANAGEMENT

@endsection

@section('breadcrumb')

    <li ><a href="{{ route('operator.dashboard') }}"> Dashboard </a></li>
    <li><a href="{{ route('operator.customer.index') }}"> Customer </a></li>
    <li><a href="{{ route('operator.customer.show', [$customer->id]) }}"> {{ $customer->name }} </a></li>
    <li><a href="{{ route('operator.subscription.show', [$order->subscription_id]) }}"> Subscription </a></li>
    <li class="active"> Details </li>

@endsection

@section('content')


    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel ">
            <div class="panel-heading"> 
                <div class="panel-title pt-2">
                    <span class="h4"> &nbsp; Order ID  {{ $order->id }}  
                    </span>
                </div>
                <span class="pull-right mr-2 h4">{{ $order->created_at->format('M d, Y, h:i A') }}</span>
                <hr class="horizonal-line-thick">
            </div>

            <div class="panel-body" style="min-height: 400px;">


                <div class="col-md-12 col-sm-12 col-xs-12 no-p">


                        <div class="table-responsive">
                            <table class="table table-condensed table-striped">
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $order->email }}</td>
                                    <th></th>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>{!! $order->status() !!} </td>
                                    <th> </th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th>Expiry Date</th>
                                    <td>{{ $order->expiry_date ? $order->expiry_date->format('M d, Y, h:i A') : '' }}</td>
                                    <th></th>
                                    <td></td>
                                </tr>

                            </table>
                        </div>
                        <div class="table-responsive">
                            <table class="table f-s-15">
                                <thead>
                                    <tr>
                                        <th>Order Item</th>
                                        <th>Description</th>
                                        <th>Qty</th>
                                        <th class="text-right">Price</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                        @foreach($order->items as $item)
                                            <tr >
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->description }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td class="text-right">{{ $item->charged  }}</td>
                                            </tr>   
                                        @endforeach    
                                        
                                    </tbody>
                                    <tfoot>
                                        @if($order->discount > 0.0)
                                            <tr >
                                                <td></td>
                                                <td>Discount</td>
                                                <td>{{ $order->discount_type }}</td>
                                                <td class="text-right"> {{ $order->discount  }} </td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <th></th>
                                            <th colspan="" >Total</th>
                                            <th colspan="2" class="text-right"> {{ $order->charged  }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                 


                </div>

            </div>


        </div>
    </div>




@endsection


@section('extra-script')    

    <script>
       
        $mn_list = $('.page-sidebar-inner ul.sidebar-nav > li.billing');
        $mn_list.addClass('active open');
        $mn_list.find('.sub-menu > .order-index').addClass('active');

        let root_url = "{{ request()->root() }}/tenant/";

        $('body').on('submit', '#order_confirmation_form',  function(event){
            event.preventDefault();
            $('#system_overlay').removeClass('hidden');

            let form = document.getElementById('order_confirmation_form');
            formData = new FormData(form);
            $('.order-form-action').addClass('hidden');
            url = "{{ route('operator.customer.billing.order.confirm', [$customer->id]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    location.reload();
                    $('#system_overlay').addClass('hidden');
                }, 3000);  

                form.reset();
                $('#system_overlay').addClass('hidden');
                
            }
            let failed = function(data){

                $('#system_overlay').addClass('hidden');
                printErrorMsg(data.error);
                $('.order-form-action').removeClass('hidden');

            }

            ajaxCall(url, formData, success, failed);  
            
        });


        $('body').on('submit', '#order_cancel_form',  function(event){
            event.preventDefault();
            $('#system_overlay').removeClass('hidden');

            let form = document.getElementById('order_cancel_form');
            formData = new FormData(form);
            $('.order-form-action').addClass('hidden');

            url = "{{ route('operator.customer.billing.order.cancel', [$customer->id]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    location.reload();
                    $('#system_overlay').addClass('hidden');
                }, 3000);  

                form.reset();
                $('#system_overlay').addClass('hidden');
                
            }
            let failed = function(data){

                $('#system_overlay').addClass('hidden');
                printErrorMsg(data.error);
                $('.order-form-action').removeClass('hidden');

            }

            ajaxCall(url, formData, success, failed);  
            
        });





    </script>


@endsection

@section('extra-css')

    <style>
       
        .status-box{
            border-radius: 6px;
            padding: 5px 8px;
            font-size: 16px;
            border: 1px solid #DEDEDE;
        }

    </style>

@endsection