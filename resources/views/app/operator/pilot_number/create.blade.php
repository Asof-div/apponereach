@extends('layouts.operator_sidebar')

@section('title')
    
    PILOT NUMBER

@endsection

@section('breadcrumb')

    <li ><a href="{{ route('operator.dashboard') }}"> Dashboard </a></li>
    <li><a href="{{ route('operator.pilot_number.index') }}"> Pilot Number </a></li>
    <li class="active"> New Pilot Number </li>

@endsection

@section('content')


    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel ">
            <div class="panel-heading"> 
                <div class="panel-title pt-2">
                    <span class="h3"> &nbsp; Add New Pilot Number  
                    </span>
                </div> 
                <span class="pull-right mr-2">
                    <a href="{{ route('operator.pilot_number.index') }}" class="btn btn-success"> <i class="fa fa-list"></i> Pilot Number List </a>
                    <a href="{{ route('operator.pilot_number.template') }}" class="btn btn-default"> <i class="fa fa-cloud-download"></i> Download Template </a>
                </span>

            </div>

            <div class="panel-body" style="min-height: 400px;">

                <hr style="background-color: #51BB8D; height: 3px;" />

                <div class="col-md-12 col-sm-12 col-xs-12 p-t-15 p-b-15 bg-silver">

                    @include('app.operator.pilot_number.partials.form')

                </div>

            </div>



        </div>
    </div>


@endsection


@section('extra-script')    

    <script>
       
        $mn_list = $('.page-sidebar-inner ul.sidebar-nav > li.pilot_number');
        $mn_list.addClass('active open');
        $mn_list.find('.sub-menu > .pilot_number-new').addClass('active');

        $('body').on('submit', '#msisdn_form',  function(event){
            event.preventDefault();
            $('#system_overlay').removeClass('hidden');

            let form = document.getElementById('msisdn_form');
            formData = new FormData(form);

            url = "{{ route('operator.pilot_number.store') }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location = "{{ route('operator.pilot_number.index') }}";
                    $('#system_overlay').addClass('hidden');
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

    </style>

@endsection