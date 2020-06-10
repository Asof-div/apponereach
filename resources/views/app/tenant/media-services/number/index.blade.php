@extends('layouts.tenant_sidebar')

@section('title')
    
    INTERCOM

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.media-service.index', [$tenant->domain]) }}"> Media Services </a></li>
    <li class="active"> Number </li>

@endsection

@section('content')
    
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading"> 
                <div class="panel-title p-b-10 p-t-10">
                    <span class="h3"> 
                        Number List ({{ $numbers->count() }})
                    </span> 
                </div>
                <hr class="horizonal-line-thick">
            </div>

            <div class="panel-body" style="min-height: 400px;">

                <div class="col-md-12 col-sm-12 col-xs-12 p-t-15 bg-silver">

                    @include('app.tenant.media-services.number.partials.table')

                </div>

            </div>



        </div>
    </div>

    @include('app.tenant.media-services.number.partials.modal')


@endsection


@section('extra-script')
    
   <script type="text/javascript">
        $mn_list = $('.sidebar ul.nav > li.nav-media-services');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-number').addClass('active');
        
        var number_id = '';
        var number = '';
        var follow_me = '';
        var scode = '';
        var scode_flow_id = '';
        var recording = '';
        var voicemail = '';
        var display_name = '';
        var user_id = '';

        $('.features-number-modal').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget);
            number_id = button.data('number_id');
            number = button.data('number_number');
            display_name = button.data('number_name');
            follow_me = button.data('number_follow_me');
            scode = button.data('number_scode');
            scode_flow_id = button.data('number_scode_flow_id');
            recording = button.data('number_recording');
            voicemail = button.data('number_voicemail');
            user_id = button.data('number_user_id');

            console.log(number);

            let modal = $(this)
            modal.find('.selected-number').html('Number: '+ number);
            
        }); 

        $('.delete-number-modal').on('show.bs.modal', function (event) {
            let modal = $(this)
            modal.find('.selected-number').html('Number: '+ number);
            modal.find('input[name=number_id]').val(number_id);
        });

        $('.edit-number-modal').on('show.bs.modal', function (event) {
            let modal = $(this)
            modal.find('input[name=number_id]').val(number_id);
            modal.find('input[name=number]').val(number);
            modal.find('input[name=name]').val(display_name);
            modal.find('input[name=user_id]').val(user_id);

        });
       
        $('.scode-number-modal').on('show.bs.modal', function (event) {
            let modal = $(this)
            modal.find('input[name=number_id]').val(number_id);
            modal.find('.selected-number').html('Number: '+ number);
            modal.find('input[name=scode_flow_id]').val(scode_flow_id);
            modal.find('input[name=scode]').val(scode);

        });



        $('body').on('submit', '#number_form',  function(event){
            event.preventDefault();

            let form = document.getElementById('number_form');
            formData = new FormData(form);

            url = "{{ route('tenant.media-service.number.store', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location = "{{ route('tenant.media-service.number.index', [$tenant->domain]) }}";
                }, 5000);  

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

            url = "{{ route('tenant.media-service.number.store', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location = "{{ route('tenant.media-service.number.index', [$tenant->domain]) }}";
                }, 5000);  


                form.reset();
                
            }
            let failed = function(data){

            }

            ajaxCall(url, formData, success, failed);  

        });

    
        $('body').on('submit', '#update_number_form',  function(event){
            event.preventDefault();

            let form = document.getElementById('update_number_form');
            formData = new FormData(form);

            url = "{{ route('tenant.media-service.number.update', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location = "{{ route('tenant.media-service.number.index', [$tenant->domain]) }}";
                }, 5000);  


                form.reset();
                
            }
            let failed = function(data){

            }

            ajaxCall(url, formData, success, failed);  

        });

            
        $('body').on('submit', '#delete_number_form',  function(event){
            event.preventDefault();

            let form = document.getElementById('delete_number_form');
            formData = new FormData(form);

            url = "{{ route('tenant.media-service.number.delete', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location = "{{ route('tenant.media-service.number.index', [$tenant->domain]) }}";
                }, 5000);  

                form.reset();
                
            }
            let failed = function(data){

            }

            ajaxCall(url, formData, success, failed);  

        });
            
        $('body').on('submit', '#scode_number_form',  function(event){
            $('#system_overlay').removeClass('hidden');
            event.preventDefault();

            let form = document.getElementById('scode_number_form');
            formData = new FormData(form);

            url = "{{ route('tenant.media-service.number.features', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location = "{{ route('tenant.media-service.number.index', [$tenant->domain]) }}";
                }, 5000);  

                form.reset();
                
            }
            let failed = function(data){

            }

            ajaxCall(url, formData, success, failed);  

        });


    </script>


@endsection


@section('extra-css')

    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery.timepicker.min.css') }}">

    <style type="text/css">
        
        .day_of_week:nth-child(even){

            background : #f2f2d2;
            padding: 10px;
            padding-left: 30px;
            margin: 10px 10px 10px 0;
            width: 70px; 
        }
        .day_of_week:nth-child(odd){

            background : #e2e2e2;
            padding: 10px;
            padding-left: 30px;
            margin: 10px 10px 10px 0;
            width: 70px; 
        }

        .day_of_week_checked{

            background: #51bb8d !important;

        }
        
        .rs-scale{
            width: 110%;
        }

        .rs-container{
            width: 95% !important;
        }

        .nav-stacked > li {
            border: 2px #51bb8d solid;
            border-radius: 4px;
            background-color: #f2f2f2;
        }

    </style>

@endsection