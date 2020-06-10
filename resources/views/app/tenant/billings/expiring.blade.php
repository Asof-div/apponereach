@extends('layouts.tenant_sidebar')

@section('title')
    
    EXPIRING/EXPIRED

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.billing.index', [$tenant->domain]) }}"> Billing Summary </a></li>
    <li class="active"> Expiring/Expired </li>

@endsection

@section('content')


    <div class="col-md-12 col-sm-12 col-xs-12 p-0">
        <div class="panel panel-default">

            <div class="panel-body" style="min-height: 500px;">
            
                <div class="col-md-12 col-sm-12 col-xs-12 p-0">
                    <hr class="horizonal-line-thick">
                    
                    @if($subscription)
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>START DATE</th>
                                    <td>{{ $subscription->start_time->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <th>EXPIRATION DATE</th>
                                    <td>{{ $subscription->end_time->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <th>CUSTOMER</th>
                                    <td> <a href="{{ route('operator.customer.show', [$subscription->tenant_id]) }}">{{ $subscription->tenant->name }} </a></td>
                                </tr>
                                <tr>
                                    <th>BILLING METHOD</th>
                                    <td>{{ strtoupper($subscription->billing_method) }}</td>
                                </tr>
                                <tr>
                                    <th>DURATION</th>
                                    <td>{{ $subscription->duration .' DAYS' }}</td>
                                </tr>
                                <tr>
                                    <th>PILOT LINE</th>
                                    <td>{{ $subscription->pilot_line }}</td>
                                </tr>
                                <tr>
                                    <th>PLAN</th>
                                    <td>{{ strtoupper($subscription->package->name) }}</td>
                                </tr>
                                <tr>
                                    <th>DESCRIPTION</th>
                                    <td>{{ $subscription->description }}</td>
                                </tr>
                                <tr>
                                    <th>AMOUNT</th>
                                    <td>{{ $subscription->currency . number_format($subscription->total, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>EXTRA MSISDN</th>
                                    <td>{!! $subscription->extra_msisdn !!}</td>
                                </tr>
                                <tr>
                                    <th>STATUS</th>
                                    <td>{!! $subscription->status() !!}</td>
                                </tr>
                                <tr>
                                    <th>PAYMENT STATUS</th>
                                    <td>{!! $subscription->payment_status() !!}</td>
                                </tr>
                                <tr>
                                    <th>ADDONS</th>
                                    <td>{!! $subscription->addons !!}</td>
                                </tr>
                                <tr>
                                    <th>ACCOUNT MANAGER</th>
                                    <td>{{ $subscription->manager ? $subscription->manager->name : '' }}</td>
                                </tr>
                            </table>
                        </div>

                        <hr class="horizonal-line-thick">
                        <div class="clearfix">
                            
                            <button type="button" data-toggle="modal" data-target=".update-subscription-modal" data-backdrop="static" class="btn btn-info"><i class="fa fa-edit"></i> UPDATE </button>
                            <span class="pull-right">
                                <button type="button" data-toggle="modal" data-target=".confirm-subscription-modal" data-backdrop="static" class="btn btn-success"><i class="fa fa-save"></i> COMFIRM SUBSCRIPTION </button>
                            </span>
                        </div>
                    @else
                        <div class="h3">No Expiring/Expired Subscription !!!</div>
                    @endif

                </div>

            </div>

        </div>
    </div>


    @if($subscription)
        <div class="modal fade update-subscription-modal" tabindex="1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header"> 
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
                        <h5 class="modal-title"> <span class="h4 text-primary"> EDIT SUBSCRIPTION ORDER </span> </h5> 
                    </div>
                    <div class="modal-body  p-15 clearfix">
                        @include('partials.validation')
                        @include('partials.flash_message')
                        <form id="expiring_form" name="13" class="form-horizontal" method="POST" action="#">
                            {{ csrf_field() }}
                            <input type="hidden" name="subscription_id" value="{{ $subscription->id }}">
                            <input type="hidden" name="tenant_id" value="{{ $subscription->tenant_id }}">
                            <div class="col-md-12 clearfix m-b-10"> 
                                <hr class="horizonal-line-thick">
                                <h4>PACKAGE INFO  <span class="text-danger"> Note!!! The price for selected package may be prorated depending on your billing method.</span></h4>
                            </div>

                            <div class="form-group clearfix">
                                <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> Package: </label>
                                <div class="col-md-8 col-xs-12">
                                    <select name="package" class="form-control">
                                        <option value=""> &dash;&dash;&dash; Select Package &dash;&dash;&dash; </option>
                                        @foreach($packages as $package)
                                            <option {{ $subscription->package_id == $package->id ? 'selected' : '' }} value="{{ $package->id }}" > {{ $package->name .', '. $package->currency.$package->price  }} </option>
                                        @endforeach 
                                    </select>
                                </div>
                            </div>

                            <div class="form-group clearfix">
                                <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> DID: </label>
                                <div class="col-md-8 col-xs-12">
                                    <span class="h4"> {{ $subscription->pilot_line }} </span>
                                </div>
                            </div>


                            <div class="form-group clearfix">
                                <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> START DATE: </label>
                                <div class="col-md-8 col-xs-12">
                                    <span class="h4"> {{ $subscription->start_time->format('d F Y') }} </span>
                                </div>
                            </div>

                            <div class="form-group clearfix">
                                <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> END DATE: </label>
                                <div class="col-md-8 col-xs-12">
                                    <span class="h4"> {{ $subscription->end_time->format('d F Y') }} </span>
                                </div>
                            </div>


                            <div class="form-group clearfix">
                                <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> BILLING METHOD: </label>
                                <div class="col-md-8 col-xs-12">
                                    <span class="h4"> {{ strtoupper($subscription->billing_method) }} </span>
                                </div>
                            </div>

                            <div class="col-md-12 clearfix m-b-10"> 
                                <hr class="horizonal-line-thick">
                                <h4>MSISDN INFO  <span class="text-danger"> Note!!! An addition MSISDN Number attacts and additional cost of &#x20A6;500 each.</span></h4>
                            </div>

                            <div class="form-group clearfix ">
                                <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> Extra MSISDN: </label>
                                <div class="col-md-8 col-xs-12">
                                    <input type="number" name="extra_msisdn" value="{{ $msisdn }}" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-12 clearfix m-b-10"> 
                                <hr class="horizonal-line-thick">
                            </div>

                            <div class="form-group clearfix ">
                                <span class="pull-left m-l-15">
                                    <button type="button" class="btn btn-default" data-dismiss="modal" > Cancel</button>
                                </span>
                                <span class="pull-right m-r-15">
                                    <button type="submit" class="btn btn-success" data-dismiss="" > <i class="fa fa-save"></i> SAVE CHANGES</button>
                                </span>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade confirm-subscription-modal" tabindex="1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header"> 
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
                        <h5 class="modal-title"> <span class="h4 text-primary"> CONFIRM SUBSCRIPTION RENEWAL ORDER </span> </h5> 
                    </div>
                    <div class="modal-body  p-15 clearfix">
                        @include('partials.validation')
                        @include('partials.flash_message')
                        <form id="renewal_form" name="13" class="form-horizontal" method="POST" action="#">
                            {{ csrf_field() }}
                            <input type="hidden" name="subscription_id" value="{{ $subscription->id }}">
                            <input type="hidden" name="tenant_id" value="{{ $subscription->tenant_id }}">
                            <div class="col-md-12 clearfix m-b-10"> 
                                <hr class="horizonal-line-thick">
                                <h4>RENEWAL INFO: <span class="text-danger"> Be sure to read the below information before confirming renewal.</span></h4>
                            </div>

                            <div class="form-group clearfix m-l-5">
                                <label class="form-label f-s-16 col-md-4">  Package: </label>
                                <div class="col-md-8 col-xs-12">
                                    <span class="h4">{{ strtoupper($subscription->package->name) }}</span>
                                </div>
                            </div>

                            <div class="form-group clearfix m-l-5">
                                <label class="form-label f-s-16 col-md-4">  DID: </label>
                                <div class="col-md-8 col-xs-12">
                                    <span class="h4"> {{ $subscription->pilot_line }} </span>
                                </div>
                            </div>


                            <div class="form-group clearfix m-l-5">
                                <label class="form-label f-s-16 col-md-4">  START DATE: </label>
                                <div class="col-md-8 col-xs-12">
                                    <span class="h4"> {{ $subscription->start_time->format('d F Y') }} </span>
                                </div>
                            </div>

                            <div class="form-group clearfix m-l-5">
                                <label class="form-label f-s-16 col-md-4">  END DATE: </label>
                                <div class="col-md-8 col-xs-12">
                                    <span class="h4"> {{ $subscription->end_time->format('d F Y') }} </span>
                                </div>
                            </div>


                            <div class="form-group clearfix m-l-5">
                                <label class="form-label f-s-16 col-md-4">  BILLING METHOD: </label>
                                <div class="col-md-8 col-xs-12">
                                    <span class="h4"> {{ strtoupper($subscription->billing_method) }} </span>
                                </div>
                            </div>

                            <div class="form-group clearfix m-l-5">
                                <label class="form-label f-s-16 col-md-4">  EXTRA MSISDN: </label>
                                <div class="col-md-8 col-xs-12">
                                    <span class="h4"> {{ strtoupper($msisdn) }} </span>
                                </div>
                            </div>



                            <div class="form-group clearfix m-l-5">
                                <label class="form-label f-s-16 col-md-4">  BILLING TOTAL: </label>
                                <div class="col-md-8 col-xs-12">
                                    <span class="h4"> {{ $subscription->currency . number_format($subscription->total, 2) }} </span>
                                </div>
                            </div>


                            <div class="col-md-12 clearfix m-b-10"> 
                                <hr class="horizonal-line-thick">
                            </div>

                            <div class="form-group clearfix ">
                                <span class="pull-left m-l-15">
                                    <button type="button" class="btn btn-default" data-dismiss="modal" > Cancel</button>
                                </span>
                                <span class="pull-right m-r-15">
                                    <button type="submit" class="btn btn-success" data-dismiss="" > <i class="fa fa-save"></i> CONFIRM RENEWAL</button>
                                </span>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif


@endsection


@section('extra-script')
    
    <script type="text/javascript">
        $mn_list = $('.sidebar ul.nav > li.nav-billing');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-expiring').addClass('active');
        

        $('body').on('submit', '#expiring_form',  function(event){
            event.preventDefault();
            $('#system_overlay').removeClass('hidden');

            let form = document.getElementById('expiring_form');
            formData = new FormData(form);

            url = "{{ route('tenant.billing.subscription.expiring.store', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location.reload();
                }, 3000);  

               
                form.reset();
                $('#system_overlay').addClass('hidden');

                
            }
            let failed = function(data){

                $('#system_overlay').addClass('hidden');
                printErrorMsg(data.error);
            }

            ajaxCall(url, formData, success, failed);  
            
        });

        $('body').on('submit', '#renewal_form',  function(event){
            event.preventDefault();
            $('#system_overlay').removeClass('hidden');

            let form = document.getElementById('renewal_form');
            formData = new FormData(form);

            url = "{{ route('tenant.billing.subscription.confirm.renewal', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location.reload();
                }, 3000);  

               
                form.reset();
                $('#system_overlay').addClass('hidden');

                
            }
            let failed = function(data){

                $('#system_overlay').addClass('hidden');
                printErrorMsg(data.error);
            }

            ajaxCall(url, formData, success, failed);  
            
        });


    </script>



@endsection

@section('extra-css')


@endsection