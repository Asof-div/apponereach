@extends('layouts.tenant_sidebar')

@section('title')
    
    TEXT TO SPEECH CONFIGURATION

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.media-service.index', [$tenant->domain]) }}"> Media Services </a></li>
    <li class="active"> Text To Speech </li>

@endsection

@section('content')

   
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading"> 
            
                <div class="panel-title p-t-10"> 
                    <span class="h3"> Total Text To Speech &nbsp; <span class="text-primary"> {{ $txttosp->count() }} </span> </span> 
                    <span class="pull-right m-r-10">
                        <a href="#" class="btn btn-lg btn-outline-default" data-toggle="modal" data-target=".tts-configuration-modal" data-backdrop="static"> <i class="fa fa-plus-circle"></i> Add Text To Speech </a>
                    </span>
                </div> 
                <hr class="horizonal-line-thick">
            </div>

            <div class="panel-body" style="min-height: 400px;">

                <div class="col-md-12 col-sm-12 col-xs-12">

                    @include('app.tenant.media-services.tts.partials.table')

                </div>

            </div>
        </div>
    </div>

        
    @include('app.tenant.media-services.tts.modals.modal')



@endsection

@section('extra-script')

    <script>

        $mn_list = $('.sidebar ul.nav > li.nav-media-services');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-tts').addClass('active');


        $('body').on('submit', '#tts_form', function(event){
            event.preventDefault();

            formData = new FormData( document.getElementById('tts_form') );
            url = "{{ route('tenant.media-service.tts.store', [$tenant->domain]) }}"; 

            let success = function(data){
                paintTable(data.txttosp);
                printFlashMsg(data.success);
                $('.modal').modal('hide');

                
            }
            let failed = function(data){

                $('#system_overlay').addClass('hidden');
            }

            ajaxCall(url, formData, success, failed); 

        });

        $('body').on('submit', '#tts_edit_form', function(event){
            event.preventDefault();

            formData = new FormData( document.getElementById('tts_edit_form') );
            url = "{{ route('tenant.media-service.tts.update', [$tenant->domain]) }}"; 

            let success = function(data){
                paintTable(data.txttosp);
                printFlashMsg(data.success);
                $('.modal').modal('hide');
            };
            let failed = function (data){
                
                $('#system_overlay').addClass('hidden');
            }

            ajaxCall(url, formData, success, failed); 


        });

        $('body').on('submit', '#tts_delete_form', function(event){
            event.preventDefault();

            formData = new FormData( document.getElementById('tts_delete_form') );
            url = "{{ route('tenant.media-service.tts.delete', [$tenant->domain]) }}"; 


            let success = function(data){

                paintTable(data.txttosp);
                printFlashMsg(data.success);
                $('.modal').modal('hide');
                
            }
            let failed = function (data){
                $('#system_overlay').addClass('hidden');
            }
            ajaxCall(url, formData, success, failed); 

        });

        $('.delete-tts-modal').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget);
            let tts = button.data('tts_id');

            var modal = $(this)
            modal.find('input[name=tts_id]').val(tts);
        });

        $('.edit-tts-modal').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget);
            let tts = button.data('tts_id');
            let title = button.data('tts_title');
            let type = button.data('tts_type');
            let content = button.data('tts_content');

            var modal = $(this)
            modal.find('input[name=tts_id]').val(tts);
            modal.find('input[name=title]').val(title);
            modal.find('select[name=type]').val(type);
            modal.find('textarea[name=text]').val(content);
        });


        function paintTable(data){
            $('.tts-list-container').empty();
            if (data.length !== undefined && data.length >= 1) {
                
                $.each(data, function (key, tts) {
                $('.tts-list-container').append(`<tr ><td> ${key + 1} </td> <td>  ${tts.title} </td> <td> ${tts.voice_code} </td><td>${tts.mime_type}</td> <td>${tts.content}</td> 
                    <td> <button class="btn btn-primary btn-xs" data-tts_type="${tts.mime_type }" data-tts_content="${ tts.content }" data-tts_title="${tts.title }" data-tts_id="${tts.id }" data-toggle="modal" data-target=".edit-tts-modal" ><i class="fa fa-edit"></i></button>
                        <button class="btn btn-danger btn-xs" data-tts_id="${ tts.id }" data-toggle="modal" data-target=".delete-tts-modal" ><i class="fa fa-trash"></i></button>
                        </td></tr>`);
                });

            } else {
                $('.tts-list-container').append("<tr> <td colspan='4'> There are no tts. </td> </tr>");
            }
        }
        
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