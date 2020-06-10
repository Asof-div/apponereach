@extends('layouts.operator_sidebar')

@section('title')
    
    CUSTOMER MANAGEMENT

@endsection

@section('breadcrumb')

    <li ><a href="{{ route('operator.dashboard') }}"> Dashboard </a></li>
    <li><a href="{{ route('operator.customer.index') }}"> Customer </a></li>
    <li class="active"> Registration </li>

@endsection

@section('content')

    <div class="col-md-12" style="margin-top: 20px;">
        <div class="steps-form">
            <div class="steps-row setup-panel-2 d-flex justify-content-between">
                <div class="steps-step ">
                    <a href="#step-1" type="button" class="btn btn-amber btn-circle waves-effect ml-0" data-toggle="tooltip" data-placement="top" title="Customer Information"><i class="fa fa-folder-open-o" aria-hidden="true"></i></a>
                    <p class="f-s-15"> Customer Information </p>
                </div>
                <div class="steps-step active">
                    <a href="#step-2" type="button" class="btn btn-blue-grey btn-circle waves-effect" data-toggle="tooltip" data-placement="top" title="Get Pilot Line"><i class="fa fa-phone" aria-hidden="true"></i></a>
                    <p class="f-s-15"> Get Pilot Number </p>
                </div>
                <div class="steps-step disabled">
                    <a href="#step-3" type="button" class="btn btn-blue-grey btn-circle waves-effect" data-toggle="tooltip" data-placement="top" title="Check Out"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
                    <p class="f-s-15"> Confirm Check Out </p>
                </div>

            </div>
        </div>

    </div>

    <div class="col-md-12 col-sm-12 col-xs-12 no-p">
        <div class="panel ">
            <div class="panel-heading"> 
                <div class="panel-title pt-2">
                    <span class="h3"> &nbsp; Customer : {{ $customer->name }}  
                    </span>
                </div> 
                <span class="pull-right mr-2">
                    <a href="{{ route('operator.customer.index') }}" class="btn btn-success"> <i class="fa fa-list"></i> Customer List </a>
                </span>

            </div>

            <div class="panel-body" style="min-height: 400px;">

                <hr class="horizonal-line-thick" />


                <div class="col-md-12 col-sm-12 col-xs-12 ">

                    <div class="table-responsive">
                        <table class="table table-condensed table-striped">
                            <tr>
                                <th>Order Type</th>
                                <th class="text-success">{{ $order->billing_type }}</th>
                                <th>Payment Method</th>
                                <td class="clearfix">{{ $order->payment_method }} 
                                    <span class="pull-right"> <a class="btn" href="{{ route('operator.customer.edit', [$customer->id]) }}"> <i class="fa fa-edit"></i> Change </a> </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>{{ $order->status }}</td>
                                <th>Due Date </th>
                                <th class="text-danger">{{ $order->due_date ? $order->due_date->format('M d, Y, h:i A') : '' }}</th>
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
                                        @if(isset($item['type']) && $item['type'] == 'DID' && $order->status == 'processing' )
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

                                            <span class="h3 text-success">&#10003;</span>
                                        @elseif(strtolower($order->status) == 'processing')
                                        
                                            <span class="h3 ">&#10042;</span>

                                        @else
                                            
                                            <span class="h3 text-danger">&#10005;</span>

                                        @endif
                                    </th>
                                </tr>
                            </tfoot>

                            
                        </table>

                    </div>
                    <div class="table-responsive">
                    @if($order->status == 'processing' && $postpaid)
                        <form id="order_confirmation_form" method="post" action="#">
                            <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                            <input type="hidden" name="billing" value="{{ $order->id }}">
                            @if(strtolower($order->billing_type) == 'registration')
                                <input type="hidden" name="post_url" value="{{ route('operator.customer.postpaid.activate') }}">

                                <input type="hidden" name="pilot_number" id="selected_pilot_number" value="{{ $pilot_number ? $pilot_number->id : '' }}" />
                                <input type="hidden" name="pilot_line" id="selected_pilot_line" value="{{ $pilot_number ? $pilot_number->number : '' }}" />
                                <input type="hidden" name="subscription" value="{{ $subscription->id }}" />


                            @endif
                            <button type="submit" class="btn btn-success" > Save Order </button>
                        </form>
                    @else

                    @endif
                    </div>
          
                </div>

            </div>


        </div>
    </div>

<div class="modal fade buy-number-modal" tabindex="-1" role="dialog" aria-labelledby="transfer_routeLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
                <h5 class="modal-title"> <span class="h3 text-info"> Select Number </span> </h5> 
            </div>
            <div class="modal-body clearfix">
                <hr style="background-color: #51BB8D; height: 3px;" />
                
                    @include('partials.validation')
                    @include('partials.flash_message')
                
                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                <input type="hidden" name="billing" value="{{ $order->id }}">

                <div class="form-group clearfix">
                    <label class="col-md-6 col-xs-12">Pilot Number</label>
                    <div class="col-md-6 col-xs-12">
                        <div class="input-group">
                            <input type="text" class="form-control" id="suggested_number" maxlength="11" placeholder="Number" aria-describedby="basic-addon2">
                            <span class="input-group-addon search-number"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                </div>

                <div class="form-group clearfix">
                    <div class="col-md-6">
                        <div class="pilot-number-container">
                            @if($pilot_number)
                                <span class='f-s-16'> {{ $pilot_number->number }} </span>
                                <span> <input type="hidden" name="pilot_number" value="{{ $pilot_number->id }}" />
                                <input type="hidden" name="pilot_line" value="{{ $pilot_number->number }}" />
                                 </span>
                                <button type='button' data-pilot_number-id="{{ $pilot_number->id }}" class='btn-link remove-reserved'> <span class='text-danger'> &times; </span> </button>
                                <div class='release-countdown' data-id="${pilot_number.id}" data-time="{{ $pilot_number->countdown }}"> </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <table class="table">
                            <tbody class="resultContent">
                                
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="form-group clearfix">
                    <hr style="background-color: #51BB8D; height: 3px;" />
                    <button type="button" class="btn btn-success" data-dismiss="modal"> <i class="fa fa-save"></i> Okay </button>
                </div>
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

        let customer = <?= json_encode($customer) ?>;
        $('body').on('submit', '#order_confirmation_form',  function(event){
            event.preventDefault();
            $('#system_overlay').removeClass('hidden');

            let form = document.getElementById('order_confirmation_form');
            formData = new FormData(form);

            url = $('#order_confirmation_form input[name=post_url]').val(); 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location = "{{ route('operator.customer.registration', [$customer->id]) }}";
                    $('#system_overlay').addClass('hidden');
                }, 5000);  

                form.reset();
                $('#system_overlay').addClass('hidden');
                
            }
            let failed = function(data){

                $('#system_overlay').addClass('hidden');
                printErrorMsg(data.error);
            }

            ajaxCall(url, formData, success, failed);  
            
        });
   
        $('body').on('click', '.search-number', function(e){

            e.preventDefault();
            $('.resultContent').empty();
            let number = $('input#suggested_number').val().trim();

            if (number.length == 0 || (number.length > 0 && /^[0-9]{11}$/.test(number))) {
                $('#system_overlay').removeClass('hidden');
                
                let type = 'Local';

                $.ajax({
                    type: 'GET',
                    url: "{{ route('operator.pilot_number.search')}}"+`?customer_id=${customer.id}&type=${type}&number=${number}`,
                    data: {},
                    success: function(data) {
                    
                        $('.resultContent').empty();

                 
                        if($.isEmptyObject(data.error)){
                    
                            // printFlashMsg(data.success);
                            

                            $.each(data.numbers, function (key, pilot_number) {
                                $('.resultContent').append("<tr > <td> " + pilot_number.number + "</td> <td>" + pilot_number.type  + "</td><td><a data-pilot_number-id='"+ pilot_number.id +"' data-pilot_number-number='"+ pilot_number.number +"' href='#' class='reserve-number' > select </a>" + " </td> </tr>");
                            });

                            $('#system_overlay').addClass('hidden');
                          
                        }else{


                            $('.resultContent').append("<tr> <td colspan='4'> There are no spare number. </td> </tr>");
                            $('#system_overlay').addClass('hidden');
                            printErrorMsg(data.error);
                          
                        }
                        $('#system_overlay').addClass('hidden');

                    },
                    error: function(e) {
                        printErrorMsg(e);
                        $('#system_overlay').addClass('hidden');

                    }
                });
            } else {
                printErrorMsg(['Search number is invalid']);
            }

        });

        $('body').on('click', '.reserve-number', function(e) {
            
            e.preventDefault();
            $('#system_overlay').removeClass('hidden');
            let pilotId = e.target.dataset.pilot_numberId;
            let pilotNumber = e.target.dataset.pilot_numberNumber;
            let $button = $(this);
            $.ajax({
                type: 'GET',
                url: "{{ route('operator.pilot_number.reserve') }}"+`?number_id=${pilotId}&customer_id=${customer.id}`,
                data: {},
                success: function(data) {
                    $('.pilot-number-container').empty();
                    $('#selected_pilot_line').val("");
                    $('#selected_pilot_number').val("");
                    if($.isEmptyObject(data.error)){
                
                        printFlashMsg(data.success);
                        $button.parent().parent().remove();

                        $.each(data.numbers, function (key, pilot_number) {
                            displayReserved('.pilot-number-container', pilot_number);
                            $('#selected_pilot_line').val(pilot_number.number);
                            $('#selected_pilot_number').val(pilot_number.id);
                        });

                        initiateCountdown();
                        $('#system_overlay').addClass('hidden');
                      
                    }else{

                        if(!$.isEmptyObject(data.numbers)){

                            $.each(data.numbers, function (key, pilot_number) {
                                displayReserved('.pilot-number-container', pilot_number);
                                $('#selected_pilot_line').val(pilot_number.number);
                                $('#selected_pilot_number').val(pilot_number.id);
                            });

                            initiateCountdown();
                            
                        }else{
                            $('.pilot-number-container').append("<span> No Result Found </span>");   
                        }

                        $('#system_overlay').addClass('hidden');
                        printErrorMsg(data.error);
                      
                    }
                    $('#system_overlay').addClass('hidden');

                },
                error: function() {
                        
                    printErrorMsg(['oops!!!']);
                    $('#system_overlay').addClass('hidden');

                }
            }); 

        });


        $('body').on('click', '.remove-reserved', function(e) {
            $('#system_overlay').removeClass('hidden');
            e.preventDefault();
            // let pilotId = e.target.dataset.pilot_numberId;
            let $button = $(this);
            let pilotId = $(this).attr('data-pilot_number-id');

            $.ajax({
                type: 'GET',
                url: "{{ route('operator.pilot_number.remove')}}"+`?number_id=${pilotId}&customer_id=${customer.id}`,
                data: {},
                success: function(data) {
                    $('.pilot-number-container').empty();
                    $('#selected_pilot_line').val("");
                    $('#selected_pilot_number').val("");
                    if($.isEmptyObject(data.error)){
                
                        printFlashMsg(data.success);
                        $button.parent().parent().remove();

                        $.each(data.numbers, function (key, pilot_number) {
                            displayReserved('.pilot-number-container', pilot_number);
                            $('#selected_pilot_line').val(pilot_number.number);
                            $('#selected_pilot_number').val(pilot_number.id);
                        });

                        initiateCountdown();
                        $('#system_overlay').addClass('hidden');
                      
                    }else{

                        if(!$.isEmptyObject(data.numbers)){

                            $.each(data.numbers, function (key, pilot_number) {
                                displayReserved('.pilot-number-container', pilot_number);
                                $('#selected_pilot_line').val(pilot_number.number);
                                $('#selected_pilot_number').val(pilot_number.id);
                            });

                            initiateCountdown();
                            
                        }else{
                            $('.pilot-number-container').append("<span> No Result Found </span>");   
                        }

                        $('#system_overlay').addClass('hidden');
                        printErrorMsg(data.error);
                      
                    }
                    $('#system_overlay').addClass('hidden');

                },
                error: function() {
                        
                    $('#system_overlay').addClass('hidden');
                    printErrorMsg(['Oops !!!']);

                }
            }); 

        });


        let countdownElements = document.getElementsByClassName('release-countdown');
        for (let i=0; i < countdownElements.length; i++) {
            countdowntTimer(countdownElements[i]);
        }

        function countdowntTimer(element)
        {
            let timer;
            let countdownElement = $(element);
            let deadline = countdownElement.attr('data-time');
            deadline = new Date(deadline * 1000);

            timer = setInterval(function() {
                var now = new Date();
                var difference = deadline.getTime() - now.getTime();

                if (difference <= 0) {
                    $(element).addClass('text-warning');
                    clearInterval(timer);
                    $(element).parent().find('button.remove-reserved').click();
                } else {
                    var seconds = Math.floor(difference / 1000);
                    var minutes = Math.floor(seconds / 60);
                    var hours = Math.floor(minutes / 60);
                    var days = Math.floor(hours / 24);

                    hours %= 24;
                    minutes %= 60;
                    seconds %= 60;



                    $(element).text(`Countdown: ${hours}h ${minutes}m  ${seconds}s `);
                    if( hours <= 0 && minutes <= 0 && seconds <= 0){
                        $(element).parent().find('button.remove-reserved').click();
                        
                    }
                    
                }
            }, 1000);
        }


        function displayReserved(target, pilot_number){
            $(target).html(`<span class='f-s-16'> ${pilot_number.number} </span>
                <span> <input type="hidden" name='pilot_number' value="${pilot_number.id}" />
                     <input type="hidden" name='pilot_line' value="${pilot_number.number}" /> </span>
                <button type='button' data-pilot_number-id="${pilot_number.id}" class='btn-link remove-reserved'> <span class='text-danger'> &times; </span> </button>
                <div class='release-countdown' data-id="${pilot_number.id}" data-time="${pilot_number.countdown}"> </div>`);   
        }

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
        .status-box{
            border-radius: 6px;
            padding: 5px 8px;
            font-size: 16px;
            border: 1px solid #DEDEDE;
        }
    </style>

@endsection