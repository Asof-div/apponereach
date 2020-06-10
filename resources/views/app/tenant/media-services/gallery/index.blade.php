@extends('layouts.tenant_sidebar')

@section('title')
    
    GALLERY CONFIGURATION

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.media-service.index', [$tenant->domain]) }}"> Media Services </a></li>
    <li class="active"> Gallery </li>

@endsection

@section('content')

   
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading"> 
            
                <div class="panel-title p-t-10"> 
                    <span class="h3"> Total Photo &nbsp; <span class="text-primary"> {{ $images->count() }} </span> </span> 
                    <span class="pull-right m-r-10">
                        <a href="#" class="btn btn-lg btn-outline-default" data-toggle="modal" data-target=".gallery-configuration-modal" data-backdrop="static"> <i class="fa fa-plus-circle"></i> Add Image </a>
                    </span>
                </div> 
                <hr class="horizonal-line-thick">
            </div>

            <div class="panel-body" style="min-height: 400px;">

                <div class="col-md-12 col-sm-12 col-xs-12">

                    @include('app.tenant.media-services.gallery.partials.list')

                </div>

            </div>
        </div>
    </div>

        
    @include('app.tenant.media-services.gallery.modals.modal')



@endsection

@section('extra-script')

    <script>

        $mn_list = $('.sidebar ul.nav > li.nav-media-services');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-gallery').addClass('active');


        $('body').on('submit', '#gallery_form', function(event){

            $('#system_overlay').removeClass('hidden');
            event.preventDefault();

            formData = new FormData( document.getElementById('gallery_form') );

            url = "{{ route('tenant.media-service.gallery.store', [$tenant->domain]) }}"; 

            let success = function(data){
              
                printFlashMsg(data.success);
                document.getElementById('gallery_form').reset();                
                displayImage(data.images);
                $('.modal').modal('hide');

                $('#system_overlay').addClass('hidden');
                
            }
            let failed = function(data){

                $('#system_overlay').addClass('hidden');
            }

            ajaxCall(url, formData, success, failed);  
            $('#system_overlay').addClass('hidden');

        });

        $('body').on('submit', '#gallery_delete_form', function(event){
            event.preventDefault();
            $('#system_overlay').removeClass('hidden');

            formData = new FormData( document.getElementById('gallery_delete_form') );
            url = "{{ route('tenant.media-service.gallery.delete', [$tenant->domain]) }}"; 


            $.ajax({
                url : url,
                type: "POST",
                data : formData,
                processData: false,
                contentType: false,
                success: function(data)
                {
                    if($.isEmptyObject(data.error)){
                        
                        displayImage(data.images);
                        printFlashMsg(data.success);
                        $('.modal').modal('hide');
                        
                    }else{
        
                        printErrorMsg(data.error);
        
                    }
                    $('#system_overlay').addClass('hidden');
                       
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    $('#system_overlay').addClass('hidden');

                    alert('error');
                }

            });

        });

        $('.delete-gallery-modal').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget);
            let gallery = button.data('gallery_id');

            var modal = $(this)
            modal.find('input[name=gallery_id]').val(gallery);
        });


        var superbox      = $('<div class="superbox-show"></div>');
        var superboximg   = $('<img src="" class="superbox-current-img">');
        var superboxclose = $('<div class="superbox-close text-danger"><button type="button" class="btn btn-transparent btn-xs"> <i class="fa fa-times"> </i> </button></div>');
        var superboxdelete = $('<div class="p-5"><span class="text-white" style="display: inline-block"> CLICK HERE TO DELETE IMAGE </span><button type="button" class="btn btn-danger gallery-delete-button" data-toggle="modal" data-target=".delete-gallery-modal" data-gallery_id=""> <i class="fa fa-trash"> </i> </button></div>');

        
        superbox.append(superboximg).append(superboxdelete).append(superboxclose);
            
        $('body').on('click','.superbox-list', function() {

    
            var currentimg = $(this).find('.superbox-img');
            var imgData = currentimg.data('img');
            var gallery = currentimg.data('gallery_id');
            var offset = $('.superbox').attr('data-offset');
                offset = (offset) ? offset : 0;
                
            superboximg.attr('src', imgData);
            superboxdelete.find('.gallery-delete-button').attr('data-gallery_id', gallery);
            $('.superbox-list').removeClass('active');
            
            if($('.superbox-current-img').css('opacity') == 0) {
                $('.superbox-current-img').animate({opacity: 1});
            }
            
            if ($(this).next().hasClass('superbox-show')) {
                superbox.toggle();
            } else {
                superbox.insertAfter(this).css('display', 'block');
                $(this).addClass('active');
            }
            
            $('html, body').animate({
                scrollTop:superbox.position().top - currentimg.width() - offset
            }, 'medium');
        
        });
                    
        $('.superbox').on('click', '.superbox-close', function() {
            $('.superbox-current-img').animate({opacity: 0}, 200, function() {
                $(this).closest('.superbox').find('.superbox-list').removeClass('active');
                $('.superbox-show').slideUp();
            });
        });

        function displayImage(data){
            $('.superbox').empty();
            if (data.length !== undefined && data.length >= 1) {
                let storage = "{{ URL::to('storage') }}";
                $.each(data, function (key, image) {
                    $('.superbox').append(`
                        <div class="superbox-list">
                            <img src="${ storage +'/'+image.source }" data-img="${ storage+'/'+ image.path }" alt="" class="superbox-img" data-gallery_id="${image.id}">
                        </div>
                    
                    `);
                });

            } else {
               
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