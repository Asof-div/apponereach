@extends('layouts.operator_sidebar')

@section('title')
    
    USER MANAGEMENT

@endsection

@section('breadcrumb')

    <li class=""><a href="{{ route('operator.dashboard') }}"> Dashboard </a></li>
    <li class=""><a href="{{ route('operator.user.index') }}"> Users </a></li>
    <li class=" active"> Create User </li>

@endsection

@section('content')


    <div class="col-md-12 col-sm-12 col-xs-12 no-p">
        <div class="panel ">
            <div class="panel-heading"> 
                <div class="panel-title">
                    <span class="h3"> &nbsp; Add New User  
                    </span> 
                </div>
                <span class="pull-right m-r-10">
                    <a href="{{ route('operator.user.index') }}" class="btn btn-lg btn-outline-default"> <i class="fa fa-list"></i> User List &nbsp; <span class="text-primary"> </span> </a>
                </span>
                
            </div>

            <div class="panel-body clearfix" style="min-height: 400px;">

                <hr style="background-color: #51BB8D; height: 3px;" />

                <div class="col-md-12 col-sm-12 col-xs-12 p-t-15 p-b-15 waves-effect waves-light">

                    @include('app.operator.user.partials.form')

                </div>

            </div>

        </div>
    </div>


@endsection


@section('extra-script')
    

    <script>
       
        $mn_list = $('.page-sidebar-inner ul.sidebar-nav > li.user');
        $mn_list.addClass('active open');
        $mn_list.find('.sub-menu > .user-new').addClass('active');


        $('body').on('submit', '#user_form',  function(event){
            event.preventDefault();
            $('#system_overlay').removeClass('hidden');

            let form = document.getElementById('user_form');
            formData = new FormData(form);

            url = "{{ route('operator.user.store') }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location = "{{ route('operator.user.index') }}";
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

    
    </script>



@endsection

@section('extra-css')

    <style>
       
        ul.nav.nav-pills li.active a{
            background: #51BB8D !important;
            color: #fff !important;
            font-weight: bold;
        }

    </style>

@endsection