@extends('layouts.tenant_sidebar')

@section('title')
    
    USER EXTENSION

@endsection

@section('breadcrumb')
    
    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.media-service.index', [$tenant->domain]) }}"> Media Services </a></li>
    <li><a href="{{ route('tenant.media-service.exten.index', [$tenant->domain]) }}"> Extensions </a></li>
    <li class="active"> View Extension  </li>

@endsection

@section('content')

   
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading"> 
                <div class="panel-title p-t-10">
                    <span class="h3"> &nbsp; Show Extension Details  &nbsp;  <span class="text-primary pull-left">
                        <a href="{{ route('tenant.media-service.exten.create', [$tenant->domain]) }}" class="btn btn-default"> <i class="fa fa-plus-circle"></i> </a> </span>
                    </span> 
                    <span class="pull-right m-r-10">
                        <a href="{{ route('tenant.media-service.exten.index', [$tenant->domain]) }}" class="btn btn-lg btn-outline-default"> <i class="fa fa-phone"></i> Extension </a>
                    </span>
                </div>
                <hr class="horizonal-line-thick">
            </div>

            <div class="panel-body" style="min-height: 400px;">

                <div class="col-md-12 col-sm-12 col-xs-12">

                    @include('app.tenant.media-services.exten.partials.details')

                </div>

            </div>



        </div>
    </div>


    <div class="modal fade edit-extension-configuration-modal" tabindex="1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header"> 
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
                    <h5 class="modal-title"> <span class="h4 text-primary"> EDIT EXTENSION CONFIGURATION </span> </h5> 
                </div>
                <div class="modal-body bg-silver p-15 clearfix">
                    @include('partials.validation')
                    @include('partials.flash_message')

                    @include('app.tenant.media-services.exten.partials.edit-form')
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade delete-extension-modal" tabindex="1" role="dialog">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <form id="extension_delete_form" method="post" action="{{ route('tenant.media-service.exten.delete', [$tenant->domain]) }}">
                    <div class="modal-header"> 
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
                        <h5 class="modal-title"> <span class="h4 text-primary"> DELETE EXTENSION </span> </h5> 
                    </div>
                    <div class="modal-body">
                        @include('partials.validation')
                        @include('partials.flash_message')                    
                        {{ csrf_field() }}
                        <p class="f-s-15"> Are you sure you want to delete this ? </p>
                        <input type="hidden" name="exten_id" value="{{ $exten->id }}">
                        <input type="hidden" name="tenant_id" value="{{ $tenant->id }}">
                    </div>
                    <div class="modal-footer">
                        <div class="form-inline">
                            <div class="form-group m-r-10">
                                <button type="button" class="btn btn-warning" data-dismiss="modal"> NO </button>
                                <button type="submit" class="btn btn-primary"> YES </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('app.tenant.media-services.exten.modals.sip_phone')


@endsection


@section('extra-script')

    <script>
       
       
        $mn_list = $('.sidebar ul.nav > li.nav-media-services');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-extension').addClass('active');

        $(function(){

            $(".phone-route>span").mask("(000) 9999-9999");


        });

       

        $('body').on('submit', '#extension_edit_form',  function(event){
            event.preventDefault();
            $('#system_overlay').removeClass('hidden');

            formData = new FormData(document.getElementById('extension_edit_form'));

            url = "{{ route('tenant.media-service.exten.update', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location = data.url;
                }, 3000);  

                
            }
            let failed = function(data){

                $('#system_overlay').addClass('hidden');
            }

            ajaxCall(url, formData, success, failed);  


        });

        $('body').on('submit', '#extension_delete_form',  function(event){
            event.preventDefault();
            $('#system_overlay').removeClass('hidden');

            formData = new FormData(document.getElementById('extension_delete_form'));

            url = "{{ route('tenant.media-service.exten.delete', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                let url = "{{ route('tenant.media-service.exten.index', [$tenant->domain]) }}";
                setTimeout(function(){ 
                    window.location = url;
                }, 3000);  

                
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

        
        
        $('.call-flow-order').sortable();

       

    
    </script>


@endsection

@section('extra-css')
        
    <style>


       .control-label{
            text-align : right;
            padding: 15px 15px 15px 25px;
        }



        .device-brand, .device{
            display: inline-block;
            max-height: 70px;
            width: 100px;
            margin: 10px;
            clear: both;
            overflow-wrap: normal;
            /*padding: 3px;*/
            position: relative;

        }
        .device-brand{
            border: 2px solid #5b5252;
            border-radius: 5px;
        }

    </style>

@endsection