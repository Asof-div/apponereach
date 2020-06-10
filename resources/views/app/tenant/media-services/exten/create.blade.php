@extends('layouts.tenant_sidebar')

@section('title')
    
    USER EXTENSION

@endsection

@section('breadcrumb')
    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.media-service.exten.index', [$tenant->domain]) }}"> Extensions </a></li>
    <li class="active"> Extension Configuration </li>

@endsection

@section('content')

   
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading"> 
                <div class="panel-title p-t-10">
                    <span class="h3"> Create Extensions </span> 
                    <span class="pull-right m-r-10">
                        <a href="{{ route('tenant.media-service.exten.index', [$tenant->domain]) }}" class="btn btn-lg btn-outline-default"> <i class="fa fa-phone"></i> Total Extension &nbsp; <span class="text-primary"> {{ $extens->count() }} </span> </a>
                    </span>

                    <hr class="horizonal-line-thick" />
                </div>
            </div>

            <div class="panel-body" style="min-height: 400px;">

                @include('app.tenant.media-services.exten.partials.form')

            </div>
        </div>
    </div>

@endsection


@section('extra-script')

    <script>
       
        $mn_list = $('.sidebar ul.nav > li.nav-media-services');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-extension').addClass('active');


        $(function(){

            $(".phone-route>span").mask("(000) 9999-9999");


        });

        $('body').on('click','#load_extension_page', function(event){
            $("#extensions_container").load("{{url($tenant->domain, 'extension-form')}}");
            $(this).addClass('hidden');
            $("#load_extension_list").removeClass('hidden');
        });

        $('body').on('click','#load_extension_list', function(event){
            $("#extensions_container").load("{{url($tenant->domain, 'extension-list')}}");
            $(this).addClass('hidden');
            $("#load_extension_page").removeClass('hidden');
        });

        $('body').on('click','.extension_edit', function(event){
            $("#extensions_container").load("{{url($tenant->domain, 'extension-edit')}}" +"/" + $(this).attr('data-exten'));
            $("#load_extension_list").removeClass('hidden');
            $("#load_extension_page").removeClass('hidden');
        });



        $('body').on('submit', '#extension_form',  function(event){
            event.preventDefault();
            $('#system_overlay').removeClass('hidden');

            formData = new FormData(document.getElementById('extension_form'));

            url = "{{ route('tenant.media-service.exten.store', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                $('#system_overlay').addClass('hidden');
                document.getElementById('extension_form').reset();
                let url = "{{ route('tenant.media-service.exten.index', [$tenant->domain]) }}";
                // setTimeout(function(){ 
                //     window.location = url;
                // }, 6000);  

                
            }
            let failed = function(data){

                $('#system_overlay').addClass('hidden');
            }

            ajaxCall(url, formData, success, failed);  
        });


        $('body').on('click', '.voicemail_toggle', function(event){
            
            if($(this).prop("checked") == true){

                $('.voicemail_body').removeClass('hidden');
            
            }else{

                $('.voicemail_body').addClass('hidden');
                
            }

        });

        $('body').on('click', '.eavesdropping_toggle', function(event){
            
            if($(this).prop("checked") == true){

                $('.eavesdropping_body').removeClass('hidden');
            
            }else{

                $('.eavesdropping_body').addClass('hidden');
                
            }

        });

        $('body').on('click', '.whispering_toggle', function(event){
            
            if($(this).prop("checked") == true){

                $('.whispering_body').removeClass('hidden');
            
            }else{

                $('.whispering_body').addClass('hidden');
                
            }

        });


        $('body').on('click', '.full_monitoring_toggle', function(event){
            
            if($(this).prop("checked") == true){

                $('.full_monitoring_body').removeClass('hidden');
            
            }else{

                $('.full_monitoring_body').addClass('hidden');
                
            }

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



       .control-label{
            text-align : right;
            padding: 15px 15px 15px 25px;
        }

        .phone-route{
            padding: 7px;
            margin: 7px;
            border-radius: 4px;
            background: #f3f3f4;
            display: inline-block;
        }

        ul.nav-tabs > li {
            width: 50%;
        }

    </style>

@endsection