@extends('layouts.tenant_sidebar')

@section('title')
    
    VIRTUAL RECEPTIONIST

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.media-service.index', [$tenant->domain]) }}"> Media Services </a></li>
    <li><a href="{{ route('tenant.media-service.virtual-receptionist.index', [$tenant->domain]) }}"> Virtual Receptionist </a></li>
    <li class="active"> View Virtual Receptionist </li>

@endsection

@section('content')

   
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading"> 
                <div class="panel-title p-t-10">
                    <span class="h3">  Show Virtual Receptionist Config                         
                    </span> 
                    <span class="pull-right m-r-10">
                        <a href="{{ route('tenant.media-service.virtual-receptionist.index', [$tenant->domain]) }}" class="btn btn-lg btn-outline-default"> <i class="fa fa-phone"></i> Total Virtual Receptionist &nbsp; <span class="text-primary"> {{ $receptionists->count() }} </span> </a>
                    </span>
                </div>
                <hr class="horizonal-line-thick">
            </div>

            <div class="panel-body" style="min-height: 400px;">

                <div class="col-md-12 col-sm-12 col-xs-12">

                    @include('app.tenant.media-services.virtual-receptionist.partials.details')

                </div>

            </div>

        </div>
    </div>


    <div class="modal fade edit-virtual-receptionist-modal" tabindex="1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header"> 
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
                    <h5 class="modal-title"> <span class="h4 text-primary"> EDIT VIRTUAL RECEPTIONIST </span> </h5> 
                </div>
                <div class="modal-body">
                    @include('partials.validation')
                    @include('partials.flash_message')

                    @include('app.tenant.media-services.virtual-receptionist.partials.edit-form')
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade delete-virtual-receptionist-modal" tabindex="1" role="dialog">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <form method="post" id="virtual_receptionist_delete_form" action="{{ route('tenant.media-service.virtual-receptionist.delete', [$tenant->domain]) }}">
                    <div class="modal-header"> 
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
                        <h5 class="modal-title"> <span class="h4 text-primary"> DELETE VIRTUAL RECEPTIONIST </span> </h5> 
                    </div>
                    <div class="modal-body clearfix">
                        @include('partials.flash_message')
                        @include('partials.validation')
                        {{ csrf_field() }}
                        <p class="f-s-15"> Are you sure you want to delete this ? </p>
                        <input type="hidden" name="receptionist_id" value="{{ $receptionist->id }}">
                        <input type="hidden" name="tenant_id" value="{{ $tenant->id }}">
                        <div class=" clearfix m-t-10">
                            <span class="selected-number f-s-16"></span>
                        </div> 
                    </div>
                    <div class="modal-footer">
                        <div class="form-inline">
                            <div class="form-group m-r-10">
                                <button type="button" class="btn btn-warning" data-dismiss="modal"> NO </button>
                                <button type="submit" class="btn btn-primary"> YES </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection


@section('extra-script')

    <script>
       
       
        $mn_list = $('.sidebar ul.nav > li.nav-media-services');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-virtual-receptionist').addClass('active');
    
        $key_options = [ 1,2,3,4,5,6,7,8,9];
        var available = [];


        let numbers = <?= json_encode($numbers); ?>;
        let extens = <?= json_encode($extens); ?>;
        let groups = <?= json_encode($groups); ?>;
        let submenus = <?= json_encode($receptionists); ?>;
        let receptionists = <?= json_encode($receptionists); ?>;
        let playbacks = <?= json_encode($sounds); ?>;
        let conferences = <?= json_encode($conferences); ?>;
        let destination = $('.destination');
        let destination_type = $('.destination-type');
        let option_key = $('.option-key');
        let storage_path = "{{ asset('storage') }}";

        let menus = <?=  json_encode($receptionist->menus); ?>;

        $('.edit-virtual-receptionist-modal').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget);
            
            var modal = $(this);
            available = [];

            if (menus.length !== undefined && menus.length >= 1) {
                $.each(menus, function (key, menu) {
                    available.push(parseInt(menu.key_press));
                    $('.ivr-menu-list').append(`
                        <tr><td> <span class='f-s-15'> Press ${menu.key_press} <input type="hidden" value="${menu.key_press}" name='option_keys[]' ></span> </td>
                        <td> <span class='p-l-10 f-s-15'> ${menu.action_label} </span> </td> 
                        <td> <span class='f-s-15'> ${menu.value} </span> <input type="hidden" name="destinations[]" value="${menu.module_id}" > <input type="hidden" name="actions[]" value="${menu.action}" > <input type="hidden" name="destination_values[]" value="${menu.action_label}" > <input type="hidden" name="params[]" value="${menu.action_label}" > </td>
                        <td> <button type='button' class='btn btn-default btn-xs remove-route' data-key="${menu.key_press}" > <i class='fa fa-times'> </i> </button> </td> </tr>
                    `);
                });

            } else {
               alert();
            }


        });
        
        $('body').on('click', '.add-route', function(e){

            let table_body = $('tbody.ivr-menu-list');
            if(table_body.find('tr').length < 9 && !isEmpty(destination.val()) && !isEmpty(destination_type.val()) && !isEmpty(option_key.val()) ){

                if(available.indexOf(parseInt(option_key.val())) == -1 ){
                    available.push(parseInt(option_key.val()));
                    
                    let tr = `<tr><td> <span class='f-s-15'> Press ${option_key.val()} <input type="hidden" value="${option_key.val()}" name='option_keys[]' ></span> </td>
                        <td> <span class='p-l-10 f-s-15'> ${destination_type.find(":selected").text()} </span> </td> 
                        <td> <span class='f-s-15'> <i class="${destination.find(":selected").data('icon')}" ></i> ${destination.find(":selected").data('content')} </span> <input type="hidden" name="destinations[]" value="${destination.val()}" > <input type="hidden" name="actions[]" value="${destination.find(":selected").attr('data-type')}" > <input type="hidden" name="destination_values[]" value="${destination.find(":selected").text()}" > <input type="hidden" name="params[]" value="${destination_type.find(":selected").text()}" > </td>
                        <td> <button type='button' class='btn btn-default btn-xs remove-route' data-key="${option_key.val()}" > <i class='fa fa-times'> </i> </button> </td> </tr>`;

                    table_body.append(tr);
                }else{

                    printErrorMsg(['Key Option '+ option_key.val() + ' is already selected'], 9000);

                }
                         
            }

        });


        $("body").on('change', 'select.ivr-select-menu', function () {

            if('t' == $(this).val().substr(0,1)){
                $('.ivr-menu-list-tts>span').html( $(this).find(":selected").attr('data-text') );
                $('.ivr-menu-list-tts>textarea').html( $(this).find(":selected").attr('data-text') );
                $('#ivr').val('yes');
                $('.ivr-menu-list-sound').addClass('hidden');
                
                $('.ivr-menu-list-tts').removeClass('hidden');
            }
            else if('s' == $(this).val().substr(0,1)){
                $('.ivr-menu-list-tts').addClass('hidden');
                $('.ivr-menu-list-tts>textarea').addClass('hidden');
                $('#ivr').val('no');
                $('.ivr-menu-list-tts>span').html('');
                $('.ivr-menu-list-tts>textarea').html('');
                $('.ivr-menu-list-sound').html(`<audio src="${$(this).find(":selected").attr('data-text')}" controls="controls"> </audio> `);
                $('.ivr-menu-list-sound').removeClass('hidden');
            }else{
                $('.ivr-menu-list-tts').addClass('hidden');
                $('.ivr-menu-list-sound').addClass('hidden');
                $('.ivr-menu-list-tts>span').html('');
                $('.ivr-menu-list-tts>textarea').html('');
                
            }

        });



        $("body").on('click', '.edit-tts', function () {

            $(this).parent().find('textarea').toggleClass('hidden');

        });

        $('body').on('click', '.remove-route', function(event){

            let td = $(this).closest('td');
            let temp = available.indexOf( parseInt( $(this).data('key') ) );
            available.splice(temp, 1);

        
            td.parent('tr').remove();
     
        });



        $("body").on('click', '.destination-type', function () {

            switch($(this).val()){
                case 'extension' :
                    $('.destination').empty();
                    extenOptions(); 
                    break;

                case 'number' :
                    $('.destination').empty();
                    numberOptions();
                    break;

                case 'group' :
                    $('.destination').empty();
                    groupOptions();
                    break;

                case 'receptionist' :
                    $('.destination').empty();
                    receptionistOptions();
                    break;

                case 'playback' :
                    $('.destination').empty();
                    playbackOptions();
                    break;

                case 'voicemail' :
                    $('.destination').empty();
                    voicemailOptions();
                    break;

                case 'conference' :
                    $('.destination').empty();
                    conferenceOptions();
                    break;


                default :
                    $('.destination').empty();
                    break;

            }

        });

        function groupOptions(){
            $('.destination').append(`<option value=""> &dash; &dash; &dash; Select Destination &dash; &dash; &dash; </option>`);
            groups.forEach(function(value, index){

                $('.destination').append(`<option value="${value.id}" data-content="${value.name + ' - ' + value.number }" data-icon="fa fa-group" data-type="group" > ${value.name + " - " + value.number } </option>`); 

            });
        }

        function numberOptions(){
            $('.destination').append(`<option value=""> &dash; &dash; &dash; Select Destination &dash; &dash; &dash; </option>`);
            numbers.forEach(function(value, index){

                $('.destination').append(`<option value="${value.id}" data-content="${value.name + ' - ' + value.number }" data-icon="fa fa-phone" data-type="number" > ${ value.number } </option>`); 

            });
        }

        function extenOptions(){
            $('.destination').append(`<option value=""> &dash; &dash; &dash; Select Destination &dash; &dash; &dash; </option>`);
            extens.forEach(function(value, index){

                $('.destination').append(`<option value="${value.id}" data-content="${value.name + ' - ' + value.number }" data-icon="fa fa-tty" data-type="extension" > ${value.name + " - " + value.number } </option>`); 

            });
        }

        function receptionistOptions(){
            $('.destination').append(`<option value=""> &dash; &dash; &dash; Select Destination &dash; &dash; &dash; </option>`);
            receptionists.forEach(function(value, index){

                $('.destination').append(`<option value="${value.id}" data-content="${value.name}" data-icon="fa fa-th-large" data-type="receptionist" > ${value.name } </option>`); 

            });
        }

        function playbackOptions(){
            $('.destination').append(`<option value=""> &dash; &dash; &dash; Select Destination &dash; &dash; &dash; </option>`);
            playbacks.forEach(function(value, index){

                let file_path = storage_path+"/"+value.path;
                $('.destination').append(`<option value="${value.id}" data-icon="fa fa-speaker" data-content="<audio src='${file_path}' controls ></audio>" data-type="playback" > ${value.title } </option>`); 

            });
        }

        function voicemailOptions(){
            $('.destination').append(`<option value=""> &dash; &dash; &dash; Select Destination &dash; &dash; &dash; </option>`);
            
            $('.destination').append(`<option value="1" data-content="Voicemail Box" data-icon="fa fa-inbox" data-type="voicemail" > Voicemail Box </option>`); 

        }

        function conferenceOptions(){
            $('.destination').append(`<option value=""> &dash; &dash; &dash; Select Destination &dash; &dash; &dash; </option>`);
            conferences.forEach(function(value, index){

                $('.destination').append(`<option value="${value.id}" data-content="${value.bridge_name + ' - ' + value.number }" data-icon="fa fa-sitemap" data-type="conference" > ${value.bridge_name + " - " + value.number } </option>`); 

            });
        }

        function isEmpty(val){
            return (val === undefined || val == null || val.length <= 0) ? true : false;
        }



        $('body').on('submit', '#virtual_receptionist_edit_form',  function(event){

            event.preventDefault();
            $("#destination").val('');
            if($('tbody.ivr-menu-list>tr').length > 0)
                $("#destination").val('12');

            formData = new FormData(document.getElementById('virtual_receptionist_edit_form'));

            url = "{{ route('tenant.media-service.virtual-receptionist.update', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location.reload();
                }, 3000);
            }
            let failed = function(data){

            }

            ajaxCall(url, formData, success, failed);  


        });


        $('body').on('submit', '#virtual_receptionist_delete_form',  function(event){

            event.preventDefault();

            formData = new FormData(document.getElementById('virtual_receptionist_delete_form'));

            url = "{{ route('tenant.media-service.virtual-receptionist.delete', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location = "{{ route('tenant.media-service.virtual-receptionist.index', [$tenant->domain]) }}";
                }, 3000);
            }
            let failed = function(data){

            }

            ajaxCall(url, formData, success, failed);  


        });

    
    </script>


@endsection

@section('extra-css')
        
    <style>


       .control-label{
            text-align : right;
            padding: 15px 15px 15px 25px;
        }

        ul.nav-tabs > li {
            width: 50%;
        }

    </style>

@endsection