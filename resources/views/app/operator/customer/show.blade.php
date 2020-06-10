@extends('layouts.operator_sidebar')

@section('title')
    
    CUSTOMER MANAGEMENT

@endsection

@section('breadcrumb')

    <li ><a href="{{ route('operator.dashboard') }}"> Dashboard </a></li>
    <li><a href="{{ route('operator.customer.index') }}"> Customer </a></li>
    <li class="active"> View Customer </li>

@endsection

@section('content')


    <div class="col-md-12 col-sm-12 col-xs-12">
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

                <hr style="background-color: #51BB8D; height: 3px;" />

                <div class="col-md-12 col-sm-12 col-xs-12 ">

                    @include('app.operator.customer.partials.details')

                </div>

            </div>

            @include('app.operator.customer.partials.modal')

        </div>
    </div>


@endsection


@section('extra-script')    

    <script>
        $mn_list = $('.page-sidebar-inner ul.sidebar-nav > li.customer');
        $mn_list.addClass('active open');
        $mn_list.find('.sub-menu > .customer-index').addClass('active');

 
        $('body').on('submit', '#suspend_customer_form',  function(event){
            event.preventDefault();
            $('#system_overlay').removeClass('hidden');

            let form = document.getElementById('suspend_customer_form');
            formData = new FormData(form);

            url = "{{ route('operator.customer.suspend', [$customer->id]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    location.reload();
                    $('#system_overlay').addClass('hidden');
                }, 3000);  
                $('.modal').modal('hide');
                form.reset();
                
            }
            let failed = function(data){

                $('.modal').modal('hide');
                $('#system_overlay').addClass('hidden');
                printErrorMsg(data.error);
            }

            ajaxCall(url, formData, success, failed);  
            
            $('#system_overlay').addClass('hidden');
        });

        $('body').on('submit', '#delete_customer_form',  function(event){
            event.preventDefault();

            $('#system_overlay').removeClass('hidden');

            let form = document.getElementById('delete_customer_form');
            formData = new FormData(form);

            url = "{{ route('operator.customer.delete', [$customer->id]) }}"; 

            let success = function(data){
                console.log(data);
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location = data.url;
                    $('#system_overlay').addClass('hidden');
                }, 3000);  
                $('.modal').modal('hide');
                form.reset();
                
            }
            let failed = function(data){

                $('.modal').modal('hide');
                $('#system_overlay').addClass('hidden');
                printErrorMsg(data.error);
            }

            ajaxCall(url, formData, success, failed);  
            
            $('#system_overlay').addClass('hidden');
        });


        $('body').on('submit', '#renew_customer_form',  function(event){
            event.preventDefault();
            $('#system_overlay').removeClass('hidden');

            let form = document.getElementById('renew_customer_form');
            formData = new FormData(form);

            url = "{{ route('operator.customer.renew', [$customer->id]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    location.reload();
                    $('#system_overlay').addClass('hidden');
                }, 3000);  
                $('.modal').modal('hide');
                form.reset();
                
            }
            let failed = function(data){

                $('.modal').modal('hide');
                $('#system_overlay').addClass('hidden');
                printErrorMsg(data.error);
            }

            ajaxCall(url, formData, success, failed);  
            
            $('#system_overlay').addClass('hidden');
        });


        $('body').on('submit', '#number_form',  function(event){
            event.preventDefault();

            let form = document.getElementById('number_form');
            formData = new FormData(form);

            url = "{{ route('operator.customer.number.store') }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                location.reload();
                }, 3000);  

                form.reset();
                
            }
            let failed = function(data){

            }

            ajaxCall(url, formData, success, failed);  

        });


        $('body').on('submit', '#number_extra_form',  function(event){
            event.preventDefault();

            let form = document.getElementById('number_extra_form');
            formData = new FormData(form);

            url = "{{ route('operator.customer.number.store') }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    location.reload();
                }, 3000);  


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