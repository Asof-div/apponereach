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
                            <div class="steps-step disabled">
                                <a href="javascript:;" type="button" class="btn btn-blue-grey btn-circle waves-effect" data-toggle="tooltip" data-placement="top" title="Select Number"> 3 </a>
                                <p class="f-s-15"> Number Selection </p>
                            </div>

                            <div class="steps-step disabled active">
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

            
            <div class="col-md-12 clearfix p-0">
                @include('app.tenant.boarding.partials.payment')
            </div>

        
        </div>
    </div>

@endsection

@section('extra-script')    

    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script src="{{ asset('js/custom_ajax/paystack.js') }}"></script>
    <script>
   
        var _token = "{{ csrf_token() }}";
        let customer = <?= json_encode($tenant) ?>;
        let subscription = <?= json_encode($subscription) ?>;
        let payment = <?= json_encode($payment) ?>;
 

        $('body').on('click', '.pay-now', function(e){
            
            $('#system_overlay').removeClass('hidden');

                let successCallBack = function(response){
                    $('.pay-now').attr('disabled', true);

                    setTimeout(function(){ 
                        window.location = "{{ route('tenant.registration.payment.callback', [$tenant->domain]) }}"+ `?trxref=${payment.transaction_no}` ; y
                    }, 3000);  

                } 

                let whenClose = function(){
                
                    printErrorMsg(['Ooop!!!.  You have cancel payment']);
                    $('.pay-now').attr('disabled', false);
                
                };
                $('#system_overlay').addClass('hidden');

                payWithPaystack(payment, successCallBack, whenClose);

            

        });
        
        function calculateFee(numbers){
            let billing_amount = 0;
            let did_amount = 0;
            $.each(subscription.billings, function (key, billing) {
                billing_amount = billing_amount + billing.charged;
            });
            $.each(numbers, function (key, pilot_number) {
                did_amount = did_amount + pilot_number.amount;
            });
            $('.total-amount').text( (parseFloat(parseFloat(billing_amount) + parseFloat(did_amount))).toLocaleString() );
            $('.did-fee').text((parseFloat(did_amount)).toLocaleString());
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