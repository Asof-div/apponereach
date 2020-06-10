@extends('layouts.tenant')

@section('content')
    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <div class="col-md-12 bg-white clearfix">
                <div class="clearfix m-t-50">
                    <div class="steps-form">
                        <div class="steps-row setup-panel-2 d-flex justify-content-between">
                            <div class="steps-step disabled ">
                                <a href="javascript:;" type="button" class="btn btn-amber btn-circle waves-effect ml-0" data-toggle="tooltip" data-placement="top" title="Plan Selection"> 1 </a>
                                <p class="f-s-15"> Plan Selection </p>
                            </div>
                            <div class="steps-step disabled ">
                                <a href="javascript:;" type="button" class="btn btn-blue-grey btn-circle waves-effect" data-toggle="tooltip" data-placement="top" title="Customer Information"> 2 </a>
                                <p class="f-s-15"> Customer Form </p>
                            </div>
                            <div class="steps-step disabled active">
                                <a href="javascript:;" type="button" class="btn btn-blue-grey btn-circle waves-effect" data-toggle="tooltip" data-placement="top" title="Select Number"> 3 </a>
                                <p class="f-s-15"> Number Selection </p>
                            </div>

                            <div class="steps-step disabled">
                                <a href="javascript:;" type="button" class="btn btn-blue-grey btn-circle waves-effect" data-toggle="tooltip" data-placement="top" title="Check-Out Order"> 4 </a>
                                <p class="f-s-15"> Payment </p>
                            </div>

                            <div class="steps-step disabled">
                                <a href="javascript:;" type="button" class="btn btn-blue-grey btn-circle waves-effect" data-toggle="tooltip" data-placement="top" title="Confirmation"> 5 </a>
                                <p class="f-s-15"> Confirmation </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="H4"> TELVIDA ONEREACH SERVICE. </h4>
                    <P class="f-s-15">CRM, Call Forward, Automatated Voice Response, Follow Me, Chat</P>
                </div>
            </div>

            
            <div class="col-md-12 clearfix p-0">)
                @include('app.tenant.boarding.partials.pilot_number')
            </div>

        
        </div>
    </div>

@endsection

@section('extra-script')    

    <script>
   
        let customer = <?= json_encode($tenant) ?>;
        let subscription = <?= json_encode($subscription) ?>;
        initiateCountdown();
        $(".phone-route").mask("(0000) 999 9999")

        $('body').on('input', '.regular-number', function(e){

            if(phoneNumberValidate($(this).val())){

            }
        });

        $('body').on('click', '.regular-search', function(e){

            $('#system_overlay').removeClass('hidden');
            e.preventDefault();
            $('#regular_search_content').empty();
            let type = 'regular';
            let number = $('input.regular-number').val();

            if(phoneNumberValidate(number)){
                $.ajax({
                    type: 'GET',
                    url: "{{ route('tenant.pilot.search', [$tenant->domain])}}"+`?number=${number}`,
                    data: {},
                    success: function(data) {
                    
                        $('#regular_search_content').empty();

                        if($.isEmptyObject(data.error)){
                    
                            
                            $.each(data.numbers, function (key, pilot_number) {
                                // $('#regular_search_content').append(`<li class='phone-route'> <i class="fa fa-phone"></i> <span class='f-s-14'> ${pilot_number.number} </span> <button type='button' data-pilot_number-id="${pilot_number.id}" data-pilot_number-number="${pilot_number.number}" class='btn reserve-number' > <i class="fa fa-plus"></i> </button> </li>`);

                                $('#regular_search_content').append(`<div id="pilot_number_${pilot_number.id}" class="list-card" draggable="true" ondragstart="dragStart(event)" data-number_id="${pilot_number.id}">
                                        <p class='phone-route'> ${pilot_number.number}  </p>
                                        <div class="card-footer">
                                            <span class="type"><i class="fa fa-file-o"></i> ${pilot_number.type}</span>
                                            <span class="price"><i class="fa fa-money"></i> &#x20A6;${pilot_number.amount}</span>
                                            <span class="cart">
                                                <button type="button" data-pilot_number-id="${pilot_number.id}" data-pilot_number-number="${pilot_number.number}" class='btn btn-xs reserve-number'> <i class="fa fa-cart-plus"></i> Select </button> 
                                            </span>
                                        </div>
                                    </div>`);

                            });
                            $(".phone-route").mask("(0000) 999 9999")
                            $('#system_overlay').addClass('hidden');
                          
                        }else{

                            printErrorMsg(['There are no spare number']);
                            $('#system_overlay').addClass('hidden');
                          
                        }
                        $('#system_overlay').addClass('hidden');

                    },
                    error: function(e) {
                        $('#system_overlay').addClass('hidden');

                    }
                });
            }else{
                printErrorMsg(['Invalid Number Format: 0700221XXXX or 0800221XXXX']);
                $('#system_overlay').addClass('hidden');
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
                url: "{{ route('tenant.pilot.reserve', [$tenant->domain]) }}"+`?number_id=${pilotId}&customer_id=${customer.id}`,
                data: {},
                success: function(data) {
                    $('.pilot-number-container').empty();
                    $('#selected_pilot_line').val("");
                    $('#selected_pilot_number').val("");
                    if($.isEmptyObject(data.error)){
                
                        printFlashMsg(data.success);
                        $button.closest('.list-card').remove();

                        $.each(data.numbers, function (key, pilot_number) {
                            displayReserved('.pilot-number-container', pilot_number);
                            
                        });
                        $('.update-badge').html(`<i class="fa fa-cart-plus"></i> <span class="badge"> ${data.numbers.length} </span>`)
                        initiateCountdown();
                        calculateFee(data.numbers);
                        $('#system_overlay').addClass('hidden');
                      
                    }else{

                        if(!$.isEmptyObject(data.numbers)){

                            $.each(data.numbers, function (key, pilot_number) {
                                displayReserved('.pilot-number-container', pilot_number);
                            });

                            initiateCountdown();
                            calculateFee(data.numbers);
                            $('.update-badge').html(`<i class="fa fa-cart-plus"></i> <span class="badge"> ${data.numbers.length} </span>`)
                            
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



                    $(element).text(`${hours}h ${minutes}m  ${seconds}s `);
                    if( hours <= 0 && minutes <= 0 && seconds <= 0){
                        $(element).parent().find('button.remove-reserved').click();
                        
                    }
                    
                }
            }, 1000);
        }


        $('body').on('click', '.remove-reserved', function(e) {
            $('#system_overlay').removeClass('hidden');
            e.preventDefault();
            // let pilotId = e.target.dataset.pilot_numberId;
            let $button = $(this);
            let pilotId = $(this).attr('data-pilot_number-id');

            $.ajax({
                type: 'GET',
                url: "{{ route('tenant.pilot.remove', [$tenant->domain])}}"+`?number_id=${pilotId}&customer_id=${customer.id}`,
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

                        $('.update-badge').html(`<i class="fa fa-cart-plus"></i> <span class="badge"> ${data.numbers.length} </span>`)
                        initiateCountdown();
                        calculateFee(data.numbers);
                        $('#system_overlay').addClass('hidden');
                          
                    }else{

                        if(!$.isEmptyObject(data.numbers)){

                            $.each(data.numbers, function (key, pilot_number) {
                                displayReserved('.pilot-number-container', pilot_number);
                                $('#selected_pilot_line').val(pilot_number.number);
                                $('#selected_pilot_number').val(pilot_number.id);
                            });

                            $('.update-badge').html(`<i class="fa fa-cart-plus"></i> <span class="badge"> ${data.numbers.length} </span>`)
                            initiateCountdown();
                            calculateFee(data.numbers);
                            
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


        function displayReserved(target, pilot_number){
            $(target).append(`
                <div class="list-card" >
                    <p> ${pilot_number.number}  </p>
                    <div class="card-footer">
                        <span class="type"><i class="fa fa-file-o"></i> ${pilot_number.type}</span>
                        <span class="price"><i class="fa fa-money"></i> &#x20A6;${pilot_number.amount}</span>
                        <span class="countdown"><i class="fa fa-clock-o"></i> <span class='release-countdown' data-id="${pilot_number.id}" data-time="${pilot_number.countdown}"> </span> </span>
                        <span class="cart"> 
                            <button type='button' data-pilot_number-id="${pilot_number.id}" class='btn btn-xs btn-danger remove-reserved'> &times; Remove </button>
                        </span>

                    </div>
                </div>`);
  
        }
        
        function calculateFee(numbers){
            let billing_amount = 0;
            let did_amount = 0.00;
            $.each(subscription.billings, function (key, billing) {
                billing_amount = billing_amount + billing.charged;
            });
            $.each(numbers, function (key, pilot_number) {
                did_amount = did_amount + parseFloat(pilot_number.amount,2);
            });
            $('.total-amount').text( (parseFloat(parseFloat(billing_amount) + parseFloat(did_amount), 2)).toLocaleString() );
            $('.did-fee').text((parseFloat(did_amount, 2)).toLocaleString());
        }

        function phoneNumberValidate(number){
            let phoneReg = /^\+?(0700)[0-9]{0,}|(0800)[0-9]{0,}$/;
            return phoneReg.test(number) && number.length <= 11;
        }

    </script>


@endsection

@section('extra-css')
    <style type="text/css">
        
        .grid-container {
            display: grid;
            grid-template-columns: auto 70px auto;
            grid-column-gap: 1rem;
            grid-row-gap: 3rem;
            font-family: 'Titillium Web', sans-serif;
        }
        .droppable {
            height: 100%;
            min-height: 400px;
            max-height: 600px;
            /*position: relative;*/
            border: 1px dotted #7f8082;
            border-radius: 3px;
            padding: 0.5rem;
            margin: 10px;
        }
        .droppable > .header{   
            border-bottom: 1px solid #7f8082;
            background-color: #FFF;
            position: relative;
            top: -15px;
            padding: 10px;
        }

        p {
            margin: 0;
            padding: 0;
        }

        .list {
            background: #e2e4e6;
            width: 100%;
            height: 100%;
            min-height: 100%;
            border-radius: 3px;
            cursor: pointer;
        }

        .list-title {
            margin: 0;
            padding: 0.4rem 0.7rem;
        }

        .cards {
            padding: 0.6rem;
            height: calc(100% - 25px);
            overflow-y: auto;
        }

        .list-card {
            background: #FFFFFF;
            border-radius: 3px;
            padding: 6px 6px 2px 8px;
            margin: 0.3rem;
            /*display: inline-block;*/
            /*width: 250px;*/
        }

        .list-card p {
            font-size: 1.5rem;
            margin-bottom: 0.2rem;
        }
        .list-card:hover{
            background: #006e53;
            color: white;
            margin: 1px dotted #7f8082;
        }

        .card-footer {
            margin-top: 5px;
            border-top: 1px solid #fbf0f0;
            display: grid;
            grid-template-columns: auto auto auto auto;
            grid-column-gap: 1rem;
            grid-row-gap: 3rem;
            font-family: 'Titillium Web', sans-serif;
        }

    </style>
@endsection