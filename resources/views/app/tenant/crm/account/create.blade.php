@extends('layouts.tenant_sidebar')

@section('title')
    
    ACCOUNT MANAGEMENT

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.crm.index', [$tenant->domain]) }}"> CRM </a></li>
    <li><a href="{{ route('tenant.crm.account.index', [$tenant->domain]) }}"> Account </a></li>
    <li class="active"> New Account </li>

@endsection

@section('content')

   
    <div class="col-md-12 col-sm-12 col-xs-12 p-0">
        <div class="panel panel-default">
            <div class="panel-heading"> 
                <div class="panel-title">
                    <span class="h3"> Create Account </span> 
                    <span class="pull-right m-r-10">
                        <a href="{{ route('tenant.crm.account.index', [$tenant->domain]) }}" class="btn btn-lg btn-outline-default"> <i class="fa fa-list"></i> Total Account &nbsp; <span class="text-primary"> {{ $accounts->count() }} </span> </a>
                    </span>
                    

                    <div class="panel-heading-btn m-r-10 m-t-10">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand" data-original-title="" title=""><i class="fa fa-expand"></i></a>
                    </div>
                </div>
            </div>

            <div class="panel-body" style="min-height: 400px;">

                    <hr style="background-color: #51BB8D; height: 3px;">

                <div class="col-md-12 col-sm-12 col-xs-12 p-t-15 bg-silver">

                    @include('app.tenant.crm.account.partials.form')

                </div>

            </div>
        </div>
    </div>

@endsection


@section('extra-script')

    <script>
       
        $mn_list = $('.sidebar ul.nav > li.nav-crm');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-account-new').addClass('active');



        $('body').on('submit', '#account_form',  function(event){
            event.preventDefault();

            $('#system_overlay').removeClass('hidden');
            let form = document.getElementById('account_form');
            formData = new FormData(form);

            url = "{{ route('tenant.crm.account.store', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location = "{{ route('tenant.crm.account.index', [$tenant->domain]) }}";
                }, 5000);  
                form.reset();
                $('#system_overlay').addClass('hidden');
                
            }
            let failed = function(data){
                
                $('#system_overlay').addClass('hidden');

            }

            ajaxCall(url, formData, success, failed);  

        });




    
    </script>


@endsection

@section('extra-css')
        
    <style>



    </style>

@endsection