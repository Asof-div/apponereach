@extends('layouts.operator_sidebar')

@section('title')
    
    USER MANAGEMENT

@endsection

@section('breadcrumb')

    <li><a href="{{ route('operator.dashboard') }}"> Dashboard </a></li>
    <li><a href="{{ route('operator.user.index') }}"> Users </a></li>
    <li class="active"> Update User </li>

@endsection

@section('content')


    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading"> 
                <div class="panel-title p-t-20">
                    <span class="h3"> &nbsp; Edit User  
                    </span> 
                    <span class="pull-right m-r-10">
                        <a href="{{ route('operator.user.index') }}" class="btn btn-lg btn-outline-default"> <i class="fa fa-list"></i> User List &nbsp; <span class="text-primary"> </span> </a>
                    </span>
                    

                    <div class="panel-heading-btn m-r-10 m-t-10">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand" data-original-title="" title=""><i class="fa fa-expand"></i></a>
                    </div>
                </div>
            </div>

            <div class="panel-body" style="min-height: 400px;">

                <hr style="background-color: #51BB8D; height: 3px;" />

                <div class="col-md-12 col-sm-12 col-xs-12 p-t-15 p-b-15 bg-silver">

                    @include('app.operator.user.partials.edit_form')

                </div>

            </div>



        </div>
    </div>


@endsection


@section('extra-script')
    
    <script>
       
        $mn_list = $('.sidebar ul.nav > li.nav-billing');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-user').addClass('active');


        $('body').on('submit', '#user_update_form',  function(event){
            event.preventDefault();

            let form = document.getElementById('user_update_form');
            formData = new FormData(form);

            url = "{{ route('operator.user.update') }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location = "{{ route('operator.user.index') }}";
                }, 5000);  

                $.gritter.add({
                    title: 'User Updated',
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