@extends('layouts.tenant_sidebar')

@section('title')
    
    ORDER MANAGEMENT

@endsection

@section('breadcrumb')

    <li ><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.billing.index', [$tenant->domain]) }}"> Billing </a></li>
    <li><a href="{{ route('tenant.billing.subscription.show', [$tenant->domain, $order->subscription_id]) }}"> Subscription </a></li>
    <li class="active"> Details </li>

@endsection

@section('content')


    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel ">
            <div class="panel-heading"> 
                <div class="panel-title pt-2">
                    <span class="h4"> &nbsp; Order ID  {{ $order->id }}  
                    </span>
                    <span class="pull-right mr-2 h4">{{ $order->created_at->format('M d, Y, h:i A') }}</span>
                </div>
                <hr class="horizonal-line-thick">
            </div>

            <div class="panel-body" style="min-height: 400px;">

                <div class="col-md-12 col-sm-12 col-xs-12 p-0">

                    <div class="col-md-9 col-sm-9 col-xs-12">

                        <div class="table-responsive">
                            <table class="table table-condensed table-striped">
                                <tr>
                                    <th>Order Type</th>
                                    <td>{{ strtoupper($order->billing_type) }}</td>
                                    <th>Payment Method</th>
                                    <td>{{ strtoupper($order->payment_method) }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>{!! $order->status() !!}</td>
                                    <th>Due Date </th>
                                    <th>{{ $order->due_date ? $order->due_date->format('M d, Y, h:i A') : '' }}</th>
                                </tr>
                                <tr>
                                    <th>Payment Status</th>
                                    <td>{!! $order->payment_status() !!}</td>
                                    <th></th>
                                    <td></td>
                                </tr>


                            </table>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-condensed table-striped">
                               
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Product</th>
                                        <th>Item</th>
                                        <th>Duration</th>
                                        <th>Amount</th>
                                        <th>Charged</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach(json_decode($order->ordered_items, true) as $index => $item)

                                        <tr>
                                            <td>{{ $index+1 }}</td>
                                            <td>
                                                <span class="display-box h4"> {{ isset($item['product']) ? $item['product'] : ''  }} </span>
                                                <span>
                                                    {{ isset($item['description']) ? $item['description'] : ''  }}    
                                                </span>
                                            </td>
                                            <td>{{ $item['items'] }}</td>
                                            <td>{{ isset($item['period']) ? $item['period'] : ''  }}</td>
                                            <td>{{ isset($item['amount']) ? $item['amount'] : ''  }}</td>
                                            <td>{{ isset($item['charged']) ? $item['charged'] : ''  }}</td>
                                            @if(isset($item['type']) && $item['type'] == 'DID' && $order->status == 'Processing' )
                                                <td>
                                                    <div class="f-s-14">
                                                        <button type="button" class="btn btn-sm btn-success text-white" data-toggle="modal" data-target=".buy-number-modal" >
                                                            <span class="text-white"> Choose your preferred Number </span>
                                                        </button>
                                                    </div>
                                                    <div class="m-t-10 f-s-16 pilot-number-container">
                                                        @if($pilot_number)
                                                        <span class="f-s-16">{{ $pilot_number->number }}</span>
                                                        <span><input type="hidden" id="selected_pilot_number" value="{{ $pilot_number->id }}"></span>
                                                        <button type='button' data-pilot_number-id="{{ $pilot_number->id }}" class='btn-link remove-reserved'> <span class='text-danger'> &times; </span> </button>
                                                        <div class='release-countdown' data-id="{{ $pilot_number->id }}" data-time="{{ $pilot_number->countdown}}"> </div>
                                                        @endif
                                                    </div>
                                                </td>
                                            @else
                                                <td>
                                                    @if( isset($item['status']) & $item['status'] == 1 )

                                                        <span class="h3 text-success">&#10003;</span>
                                                    @else
                                                        
                                                        <span class="h3 text-danger">&#10005;</span>

                                                    @endif

                                                </td>
                                            @endif
                                            
                                        </tr>

                                    @endforeach
                                    
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th colspan="4"> <span class="h3"> Subtotal </span></th>
                                        <th>{{ $order->amount }}</th>
                                        <th>{{ $order->charged }}</th>
                                        <th>
                                            @if( strtolower($order->status) == 'success' )

                                                <span class="label f-s-18 text-success">&#10003;</span>
                                            @elseif(strtolower($order->status) == 'processing')
                                            
                                                <span class="label f-s-18">&#10042;</span>

                                            @else
                                                
                                                <span class="label f-s-18 text-danger">&#10005;</span>

                                            @endif
                                        </th>
                                    </tr>
                                </tfoot>

                                
                            </table>

                        </div>

                    </div>

                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="table-responsive">
                                                
                            <table class="table table-condensed table-striped">

                                <tr>
                                    <th>Domain</th>
                                    <td>{{ $tenant->domain }}</td>
                                </tr>
                                <tr>
                                    <th>tenant No.</th>
                                    <td>{{ $tenant->tenant_no }}</td>
                                </tr>
                                <tr>
                                    <th>Domain Account</th>
                                    <td>{{$tenant->info->email}}</td>
                                </tr>
    
                                <tr>
                                    <th> Business Name</th>
                                    <td> 
                                        <span>{{$tenant->name}} 
                                    </td>
                                </tr>    
                                <tr>
                                    <th>Status</th>
                                    <th>{{$tenant->status? "ACTIVATED" : "DEACTIVATED"}}</th>
                                </tr>
                                <tr>
                                    <th>Valid</th>
                                    <th>{{$tenant->validity }}</th>
                                </tr>
                             
                            </table>


                        </div>                
                    </div>
                       


                </div>

            </div>


        </div>
    </div>



@endsection


@section('extra-script')    

    <script>
       
        $mn_list = $('.sidebar ul.nav > li.nav-billing');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-subscription').addClass('active');



    </script>


@endsection

@section('extra-css')

    <style>
       
        ul.nav.nav-pills li.active a{
            background: #51BB8D !important;
            color: #fff !important;
            font-weight: bold;
        }
        .p-l-15{
            padding-left: 15px !important;
        }

        .display-box{
            display: block;

        }
        .status-box{
            border-radius: 6px;
            padding: 5px 8px;
            font-size: 16px;
            border: 1px solid #DEDEDE;
        }

    </style>

@endsection