@extends('layouts.tenant_sidebar')

@section('title')
    
    opportunity MANAGEMENT

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.crm.index', [$tenant->domain]) }}"> CRM </a></li>
    <li><a href="{{ route('tenant.crm.opportunity.index', [$tenant->domain]) }}"> CRM </a></li>
    <li class="active"> Opportunity </li>

@endsection

@section('content')

   
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading"> 
            
                <div class="panel-title"> 
                    <span class="h3"> Total Opportunities &nbsp; <span class="text-primary"> {{ $opportunities->count() }} </span> </span> 
                    <span class="pull-right m-r-10">
                        <a href="{{ route('tenant.crm.opportunity.create', [$tenant->domain]) }}" class="btn btn-lg btn-outline-default"> <i class="fa fa-plus-circle"></i> Add opportunity </a>
                    </span>

                    <div class="panel-heading-btn m-r-10 m-t-10">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand" data-original-title="" title=""><i class="fa fa-expand"></i></a>
                    </div>
                </div> 

            </div>

            <div class="panel-body" style="min-height: 400px;">

                    <hr style="background-color: #51BB8D; height: 3px;">

                <br>


                <div class="col-md-12 col-sm-12 col-xs-12 bg-silver" >

                    @include('app.tenant.crm.opportunity.partials.form')

                </div>

            </div>
        </div>
    </div>

        



@endsection


@section('extra-script')
    <script type="text/javascript" src="{{ URL::to('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
    <script>
       
        $mn_list = $('.sidebar ul.nav > li.nav-crm');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-opportunity').addClass('active');


        $(function () {
            let today = new Date();
            $('.datepicker').datepicker({
                format:'yyyy-m-d',
                // rangeLow: today.getFullYear() + today.getMonth() + today.getDay(),
                defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 3,
                minDate: today,
                onSelect: function(selectedDate) {
                    let option = this.id == "from" ? "minDate" : "maxDate",
                        instance = $(this).data("datepicker"),
                        date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
                    dates.not(this).datepicker("option", option, date);
                }
            });


        });
    
        $("body").on('click', '.period', function(event){
            if($(this).val() == 'Once' ){
                $('.cycle-box').addClass('hidden');
            }else{
                $('.cycle-box').removeClass('hidden');
            }

        });

        $("body").on('click', '.toggle-account', function(event){
            
            $('.account-form-box').toggleClass('hidden');
            $('.save-opportunity').toggleClass('hidden');

        });

        $("body").on('click', '.close-account', function(event){
            
            $('.account-form-box').addClass('hidden');
            $('.save-opportunity').removeClass('hidden');

        });


        $('body').on('submit', '#opportunity_form',  function(event){
            event.preventDefault();
            let form = document.getElementById('opportunity_form');
            let formData = new FormData(form);

            url = "{{ route('tenant.crm.opportunity.store', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 

                }, 5000);  

                $.gritter.add({
                    title: 'Opportunity Created',
                    text: data.success,
                    sticky: false,
                    time: '8000'
                });
                form.reset();
                
            }
            let failed = function(data){

            }

            ajaxCall(url, formData, success, failed);  

        });

        $('body').on('submit', '#account_form',  function(event){
            event.preventDefault();

            let form = document.getElementById('account_form');
            let formData = new FormData(form);

            url = "{{ route('tenant.crm.account.store', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);

                $('.close-account').click();

                let accounts = data.accounts;
                let account = data.account;
                $('.account-select').empty();

                accounts.forEach(function(value, index){

                    if(account.id == value.id){
                        $('.account-select').append(`<option selected='selected' value="${value.id}" > ${value.name } </option>`); 

                    }else{
                        $('.account-select').append(`<option value="${value.id}" > ${value.name } </option>`); 
                    }

                });

                $.gritter.add({
                    title: 'Account Created',
                    text: data.success,
                    sticky: false,
                    time: '5000'
                });
                form.reset();

            }
            let failed = function(data){

            }

            ajaxCall(url, formData, success, failed);  

        });

        



    </script>


@endsection

@section('extra-css')
        
    <style>

        .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
        }

        .switch input {display:none;}

        .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
        }

        .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
        }

        input:checked + .slider {
        background-color: #2196F3;
        }

        input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
        border-radius: 34px;
        }

        .slider.round:before {
        border-radius: 50%;
        }


    </style>

@endsection