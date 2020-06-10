@extends('layouts.tenant_sidebar')

@section('title')
    
    USER MANAGEMENT

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.account.user.index', [$tenant->domain]) }}"> Users </a></li>
    <li class="active"> Create User </li>

@endsection

@section('content')


    <div class="col-md-12 col-sm-12 col-xs-12 p-0">
        <div class="panel panel-default">
            <div class="panel-heading"> 
                <div class="panel-title p-t-20">
                    <span class="h3"> &nbsp; Add New User  
                    </span> 
                    <span class="pull-right m-r-10">
                        <a href="{{ route('tenant.account.user.index', [$tenant->domain]) }}" class="btn btn-lg btn-outline-default"> <i class="fa fa-list"></i> User List &nbsp; <span class="text-primary"> </span> </a>
                    </span>
                    

                    <div class="panel-heading-btn m-r-10 m-t-10">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand" data-original-title="" title=""><i class="fa fa-expand"></i></a>
                    </div>
                </div>
            </div>

            <div class="panel-body" style="min-height: 400px;">

                <hr style="background-color: #51BB8D; height: 3px;" />

                <div class="col-md-12 col-sm-12 col-xs-12 p-t-15 p-b-15 bg-silver">

                    @include('app.tenant.account.user.partials.form')

                </div>

            </div>



        </div>
    </div>


@endsection


@section('extra-script')

    <script>
       
        $mn_list = $('.sidebar ul.nav > li.nav-account');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-users').addClass('active');


        $('body').on('submit', '#user_form',  function(event){
            event.preventDefault();

            let form = document.getElementById('user_form');
            formData = new FormData(form);

            url = "{{ route('tenant.account.user.store', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location = "{{ route('tenant.account.user.index', [$tenant->domain]) }}";
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
       


    </style>

@endsection