@extends('layouts.tenant')

@section('title')

    SIGN UP PROCESS

@endsection

@section('breadcrumb')
    
    <li><a href="{{ route('tenant.index', [$tenant->domain]) }}">{{ $tenant->name }}</a></li>
    <li class="active"> Activation </li>

@endsection

@section('content')


    <div class="row">
        
        <div class="col-md-8">

            <div class="panel panel-white">

                <div class="panel-heading"> 
            
                    <div class="panel-title"> 
                        <span class="h3"></span> 

                        <hr style="background-color: #51BB8D; height: 3px;">
     
                    </div> 

                </div>
            
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="h3">Package Registration</div>
                                        <span>{{ $package->name }}</span>
                                    </td>
                                    <td>
                                        <span class="f-s-16">
                                            {{ $subscription->duration / 30  }} Month
                                        </span>
                                    </td>
                                    <td>
                                        <span>{{ $subscription->currency }}</span> <span>{{ $subscription->amount }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="h3">Pilot Number</div>
                                        <span> Free </span>
                                    </td>
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
                                    <td>
                                        <span>{{ $subscription->currency }}</span> <span>0.00</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            
            </div>

        </div>

        <div class="col-md-4">

            <div class="panel panel-white">
            
                <div class="panel-heading"> 
            
                    <div class="panel-title"> 
                        <span class="h3"> Your Subtotal </span> 

                        <hr style="background-color: #51BB8D; height: 3px;">
     
                    </div> 

                </div>

                <div class="panel-body" >

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td>
                                        <span class="h4"> Billing: </span>
                                        <span>{{ $billing->id }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="h4"> Name: </span>
                                        <span class="f-s-15">{{ $billing->name }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="h4"> Email: </span>
                                        <span class="f-s-15">{{ $billing->email }}</span>
                                    </td>
                                </tr>
                                <tr>                
                                    <td>
                                        <span class="h4"> Subtotal: {{ $subscription->currency }}</span> <span class="f-s-16">{{ number_format($subscription->amount, 2) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <button type="button" class="btn btn-success btn-block pay-now" >Pay Now</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            
            </div>

        </div>
    </div>


    <div class="modal fade buy-number-modal" tabindex="1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> <h5 class="modal-title"> <span class="h4 text-primary"> Buy Number </span> </h5> </div>
                <div class="modal-body clearfix">
                    <div class="col-md-12 form-inline clearfix">  
                        <div class="form-group m-r-10">
                            <select class="form-control" id="pilot_number_type" >
                                <option value="Local">Local</option>
                                <option value="toll free">Toll Free</option>
                            </select>
                        </div><div class="form-group m-r-10">
                            <input class="form-control" id="suggested_number" placeholder="080" type="text">
                        </div>                       

                    </div>

                    <div class="col-md-12 m-t-5">
                        <div class="table-responsive">
                            <table class="table table-condensed table-hover">
                                <tbody class="resultContent"></tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <div class="form-inline">
                        <div class="checkbox m-r-10 pull-left">
                            <label>
                                <input type="checkbox"> Display Only Sequential Numbers.
                            </label>
                        </div>
                        <div class="form-group m-r-10">
                            <button type="button" class="btn btn-primary search-number"> <i class="fa fa-search"></i> Search </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('extra-script')

    <script src="https://js.paystack.co/v1/inline.js"></script>
    
    <script src="{{ asset('js/custom_ajax/paystack.js') }}"></script>
    
    <script type="text/javascript">
        
        var _token = "{{ csrf_token() }}";
        let billing = <?= json_encode($billing); ?>;
        let tenant = <?= json_encode($tenant); ?>;
        let subscription = <?= json_encode($subscription); ?>;

        $(function(){
            let pilot_number = $('#selected_pilot_number').val();
            if( pilot_number != '' && pilot_number != undefined ){

                var formdata = new FormData();
                formdata.append("_token", _token);
                formdata.append("reference", billing.id);
                formdata.append("billing", billing.id);
                formdata.append("tenant", tenant.id);
                formdata.append("subscription", subscription.id);
                formdata.append("pilot_number", pilot_number);


                var url = "{{ route('tenant.verify', [$tenant->domain]) }}"; 
                 
                let success = function(data){
                    
                    printFlashMsg(data.success);
                    setTimeout(function(){ 
                        window.location = "{{ route('tenant.index', [$tenant->domain]) }}";
                    }, 5000);  

                    $.gritter.add({
                        title: 'Account Activated',
                        text: data.success,
                        sticky: false,
                        time: '8000'
                    });

                    $('.pay-now').attr('disabled', false);
                };

                
                let failed = function(data){

                    printErrorMsg(['Ooop!!!.  Unable to activate account']);
                    $('.pay-now').attr('disabled', false);

                };

                ajaxCall(url, formdata, success, failed);  

            }else{

                printErrorMsg(['Ooop!!!.  You have no select any pilot number']);

            }
        
        });

        $('body').on('click', '.search-number', function(e){

            e.preventDefault();
            $('.resultContent').empty();
            var type = $('select#pilot_number_type').val();
            var number = $('input#suggested_number').val();

            $.ajax({
                type: 'GET',
                url: "{{ route('tenant.pilot.search', [$tenant->domain])}}"+"?type="+type+"&number="+number,
                data: {},
                success: function(data) {
                
                    $('.resultContent').empty();

                    if (data.length !== undefined && data.length >= 1) {
                        
                        $.each(data, function (key, pilot_number) {
                            $('.resultContent').append("<tr > <td> " + pilot_number.number + "</td> <td>" + pilot_number.type  + "</td><td><a data-pilot_number-id='"+ pilot_number.id +"' data-pilot_number-number='"+ pilot_number.number +"' href='#' class='reserve-number' data-dismiss='modal'> select </a>" + " </td> </tr>");
                        });

                    } else {
                        $('.resultContent').append("<tr> <td colspan='4'> There are no spare number. </td> </tr>");
                    }
                },
                error: function(e) {
                        console.log(e);
                        alert(e);

                }
            });

        });

        $('body').on('click', '.reserve-number', function(e) {
            
            e.preventDefault();
            let pilotId = e.target.dataset.pilot_numberId;
            let pilotNumber = e.target.dataset.pilot_numberNumber;
            let $button = $(this);
            $.ajax({
                type: 'GET',
                url: "{{ route('tenant.pilot.reserve', [$tenant->domain])}}"+"?number_id="+pilotId,
                data: {},
                success: function(data) {
                    $('.pilot-number-container').empty();

                    if (data.length !== undefined && data.length >= 1) {
                        
                        $button.parent().parent().remove();

                        $.each(data, function (key, pilot_number) {
                            $('.pilot-number-container').html(`<span class='f-s-16'> ${pilot_number.number} </span>
                                <span> <input type="hidden" id="selected_pilot_number" value="${pilot_number.id}" /> </span>
                                <button type='button' data-pilot_number-id="${pilot_number.id}" class='btn-link remove-reserved'> <span class='text-danger'> &times; </span> </button>
                                <div class='release-countdown' data-id="${pilot_number.id}" data-time="${pilot_number.countdown}"> </div>`);
                        });

                        let countdownElements = document.getElementsByClassName('release-countdown');
                        for (let i=0; i < countdownElements.length; i++) {
                            countdowntTimer(countdownElements[i]);
                        }
                        $('.payment-option').removeClass('hidden');


                    } else {
                        $('.pilot-numbers-in-cart').append("<tr> <td colspan='3'> No Result Found </td> </tr>");
                        $('.payment-option').addClass('hidden');
                    }
                },
                error: function() {
                        
                    alert('error');

                }
            }); 

        });


        $('body').on('click', '.remove-reserved', function(e) {
            e.preventDefault();
            let pilotId = e.target.dataset.pilot_numberId;
            let $button = $(this);
            $.ajax({
                type: 'GET',
                url: "{{ route('tenant.pilot.remove', [$tenant->domain])}}"+"?number_id="+pilotId,
                data: {},
                success: function(data) {
                    $('.pilot-number-container').empty();

                    if (data.length !== undefined && data.length >= 1) {
                        
                        $button.parent().parent().remove();
                        
                        $.each(data, function (key, pilot_number) {
                            
                            $('.pilot-number-container').html(`<span class='f-s-16'> ${pilot_number.number} </span>
                                <span> <input type="hidden" id="selected_pilot_number" value="${pilot_number.id}" /> </span>
                                <button type='button' data-pilot_number-id="${pilot_number.id}" class='btn-link remove-reserved'> <span class='text-danger'> &times; </span> </button>
                                <div class='release-countdown' data-id="${pilot_number.id}" data-time="${pilot_number.countdown}"> </div>`);

                        });

                        let countdownElements = document.getElementsByClassName('release-countdown');
                        for (let i=0; i < countdownElements.length; i++) {
                            countdowntTimer(countdownElements[i]);
                        }
                        $('.payment-option').removeClass('hidden');

                    } else {
                        $('.pilot-numbers-in-cart').append("<tr> <td colspan='3'> No Result Found </td> </tr>");
                        $('.payment-option').addClass('hidden');
                    }
                },
                error: function() {
                        
                    alert('error');

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



        $('body').on('click', '.pay-now', function(e){
            
            $('#system_overlay').removeClass('hidden');

            let pilot_number = $('#selected_pilot_number').val();
            if( pilot_number != '' && pilot_number != undefined ){

                let successCallBack = function(response){
                    $('.pay-now').attr('disabled', true);
                    var formdata = new FormData();
                    formdata.append("_token", _token);
                    formdata.append("reference", billing.id);
                    formdata.append("billing", billing.id);
                    formdata.append("tenant", tenant.id);
                    formdata.append("subscription", subscription.id);
                    formdata.append("pilot_number", pilot_number);

                    let url = "{{ route('tenant.verify', [$tenant->domain]) }}"; 
                     
                    let success = function(data){
                        
                        
                        printFlashMsg(data.success);
                        setTimeout(function(){ 
                            window.location = "{{ route('tenant.index', [$tenant->domain]) }}";
                        }, 5000);  

                        $.gritter.add({
                            title: 'Account Activated',
                            text: data.success,
                            sticky: false,
                            time: '8000'
                        });

                        $('.pay-now').attr('disabled', false);
                        $('#system_overlay').addClass('hidden');

                    }
                    let failed = function(data){

                        $('#system_overlay').addClass('hidden');
                        printErrorMsg(['Ooop!!!.  Unable to activate account']);
                        $('.pay-now').attr('disabled', false);

                    }
                    $('#system_overlay').removeClass('hidden');

                    ajaxCall(url, formdata, success, failed);  


                } 

                let whenClose = function(){
                
                    printErrorMsg(['Ooop!!!.  You have cancel payment']);
                    $('.pay-now').attr('disabled', false);
                
                };
                $('#system_overlay').addClass('hidden');

                payWithPaystack(billing, successCallBack, whenClose);

            }
            else{

                $('#system_overlay').addClass('hidden');
                printErrorMsg(['Ooop!!!.  You have no select any pilot number']);

            }

        });


    </script>

@endsection