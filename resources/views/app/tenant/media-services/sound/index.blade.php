@extends('layouts.tenant_sidebar')

@section('title')
    
    SOUND CONFIGURATION

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.media-service.index', [$tenant->domain]) }}"> Media Services </a></li>
    <li class="active"> SOUND </li>

@endsection

@section('content')

   
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading"> 
            
                <div class="panel-title p-t-10"> 
                    <span class="h3"> Total Sounds &nbsp; <span class="text-primary"> {{ $sounds->count() }} </span> </span> 
                    <span class="pull-right m-r-10">
                        <a href="#" class="btn btn-lg btn-outline-default" data-toggle="modal" data-target=".sound-configuration-modal" data-backdrop="static"> <i class="fa fa-plus-circle"></i> Add Sound </a>
                    </span>
                </div> 
                <hr class="horizonal-line-thick">
            </div>

            <div class="panel-body" style="min-height: 400px;">

                <div class="col-md-12 col-sm-12 col-xs-12 clearfix">
                    <span class="f-s-15"> Please Dial 111111 for call to record </span>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12 clearfix">

                    @include('app.tenant.media-services.sound.partials.table')

                </div>

            </div>
        </div>
    </div>

        
    @include('app.tenant.media-services.sound.modals.modal')



@endsection

@section('extra-script')

    <script>

        $mn_list = $('.sidebar ul.nav > li.nav-media-services');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-sound').addClass('active');


        $('body').on('submit', '#sound_form', function(event){
            event.preventDefault();

            formData = new FormData( document.getElementById('sound_form') );

            $('#system_overlay').removeClass('hidden');

            url = "{{ route('tenant.media-service.sound.store', [$tenant->domain]) }}"; 



            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location.reload(); 
                }, 4000);  
                document.getElementById('sound_form').reset();
                $('#system_overlay').addClass('hidden');

                
            }
            let failed = function(data){
                printErrorMsg(data.error);
                $('#system_overlay').addClass('hidden');

            }

            ajaxCall(url, formData, success, failed); 



        });

        $('body').on('submit', '#sound_delete_form', function(event){
            event.preventDefault();

            formData = new FormData( document.getElementById('sound_delete_form') );

            $('#system_overlay').removeClass('hidden');

            url = "{{ route('tenant.media-service.sound.delete', [$tenant->domain]) }}"; 

             let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location.reload(); 
                }, 4000);  
                document.getElementById('sound_form').reset();
                $('#system_overlay').addClass('hidden');

                
            }
            let failed = function(data){
                printErrorMsg(data.error);
                $('#system_overlay').addClass('hidden');

            }

            ajaxCall(url, formData, success, failed); 


        });

        $('.delete-sound-modal').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget);
            let sound = button.data('sound_id');

            var modal = $(this)
            modal.find('input[name=sound_id]').val(sound);
        });


        $('body').on('click', '#record_over_phone', function(){

            $('.file_option_cont').addClass('hide');
            $('.title-form').removeClass('hide');
            $('#file_option_phone').removeClass('hide');

        });


        $('body').on('click', '#file_upload', function(){

            $('.file_option_cont').addClass('hide');
            $('.title-form').addClass('hide');
            $('#file_option_upload').removeClass('hide');
             
        });

    
    </script>


@endsection

@section('extra-css')
        
    <style>

       .control-label{
            text-align : right;
            padding: 15px 15px 15px 25px;
        }


    </style>

@endsection