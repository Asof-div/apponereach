@extends('layouts.tenant_sidebar')

@section('title')
    
    CALL FLOW 

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li class="active"> Call Flow </li>

@endsection

@section('content')


    <div class="col-md-12 col-sm-12 col-xs-12 p-0">
        <div class="panel panel-default">
            <div class="panel-heading"> 
                <div class="panel-title p-b-10 p-t-10">
                    <span class="h3"> 
                        Call Flow List ({{ $call_routes->count() }})
                    </span> 
                    <span class="pull-right m-r-10">
                        <a href="{{ route('tenant.media-service.call-flow.create', [$tenant->domain]) }}" class="btn btn-lg btn-outline-default"> New Call Flow </a>
                    </span>
                </div>
                <hr class="horizonal-line-thick">
            </div>

            <div class="panel-body" style="min-height: 400px;">

                <div class="col-md-12 col-sm-12 col-xs-12">

                    @include('app.tenant.media-services.call_flow.partials.table')

                </div>

            </div>



        </div>
    </div>

    <div class="modal fade delete-call-flow-modal" tabindex="1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <form method="post" action="{{ route('tenant.media-service.call-flow.delete', [$tenant->domain]) }}">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span> </button>
                        <h5 class="modal-title"> <span class="h4 text-primary"> DELETE CALL FLOW </span> </h5>
                    </div>

                    <div class="modal-body">
                     
                        {{ csrf_field() }}
                        <p class="f-s-15"> Are you sure you want to delete this ? </p>
                        <input type="hidden" name="route_id" value="">
                         
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

    <div class="modal fade edit-call-flow-modal" tabindex="1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span> </button>
                    <h5 class="modal-title"> <span class="h4 text-primary"> UPDATE CALL FLOW </span> </h5>
                </div>

                <div class="modal-body">
                 
                    @include('app.tenant.media-services.call_flow.partials.edit_form')
                     
                </div>

            </div>            
        </div>
    </div>
    
@endsection


@section('extra-script')

    <script type="text/javascript">

        $mn_list = $('.sidebar ul.nav > li.nav-media-services');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-call-flow').addClass('active');


        $('.delete-call-flow-modal').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget);
            let route = button.data('route_id');

            var modal = $(this)
            modal.find('input[name=route_id]').val(route);
        });

        $('.edit-call-flow-modal').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget);
            let route = button.data('route_id');
            let title = button.data('title');
            let ring_time = button.data('ring_time');
            let record = button.data('record');
            let voicemail = button.data('voicemail');
            let greeting = button.data('greeting');
            let module = button.data('module');
            let value = button.data('value');
            let action = button.data('action');
            let module_id = button.data('module_id');

            var modal = $(this)
            modal.find('input[name=route_id]').val(route);
            modal.find('input[name=title]').val(title);
            modal.find('input[name=ring_time]').val(ring_time);
            modal.find('input[name=record]').prop('checked', record);
            modal.find('input[name=voicemail]').prop('checked', voicemail);
            modal.find('select[name=greeting]').val(greeting).change();
            modal.find('input[name=module]').val(module);
            modal.find('select[name=destination_type]').val(action).click();
            modal.find('input[name=destination_value]').val(value);
            modal.find('select[name=destination]').val(module_id);

        });


        let numbers = <?= json_encode($numbers); ?>;
        let extens = <?= json_encode($extens); ?>;
        var groups = <?= json_encode($groups); ?>;
        let receptionists = <?= json_encode($receptionists); ?>;
        let playbacks = <?= json_encode($sounds); ?>;

        $("body").on('change', 'select.greeting-select', function () {

            if('t' == $(this).val().substr(0,1)){
                $('.greeting-tts>span').html( $(this).find(":selected").attr('data-text') );
                $('.greeting-tts>textarea').html( $(this).find(":selected").attr('data-text') );
                $('#greet').val('yes');
                $('.greeting-tts').removeClass('hidden');
                $('.sound-path').val("");
            }else{
                $('.greeting-tts').addClass('hidden');
                $('.greeting-tts>textarea').addClass('hidden');
                $('#greet').val('no');
                $('.sound-path').val( $(this).find(":selected").attr('data-path') );


                $('.greeting-tts>span').html('');
                $('.greeting-tts>textarea').html('');
                
            }

        });

        $('body').on('input','.greeting-tts>textarea', function(){

           $('.playback-message>p.tts-message').html($(this).val());
        
        });

        $("body").on('click', 'select.greeting-select', function () {

            switch($(this).find(":selected").attr('data-type')){
                case 'tts' :
                    $('.playback-message').html(`<p class="tts-message" > ${$(this).find(":selected").attr('data-text')} </p> `);
                    $('.sound-path').val("");

                    break;

                case 'sound' :
                    $('.playback-message').html(`<audio src="${$(this).find(":selected").attr('data-text')}" controls="controls"> </audio> `);
                    $('.sound-path').val( $(this).find(":selected").attr('data-path') );

                    break;
                    $('.playback-message').html(``);
                default :
                    break;

            }
        });


        $("body").on('click', '.edit-tts', function () {

            $(this).parent().find('textarea').toggleClass('hidden');

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

                default :
                    $('.destination').empty();
                    break;

            }

        });

        $("body").on('click', '.destination', function(){

            switch($(this).find(":selected").attr('data-type')){
                case 'extension' :
                    $('.action-call').empty();
                    extenSelected($(this).val());
                    break;

                case 'number' :
                    $('.action-call').empty();
                    numberSelected($(this).val());
                    break;

                case 'group' :
                    $('.action-call').empty();
                    groupSelected($(this).val());
                    break;

                case 'receptionist' :
                    $('.action-call').empty();
                    receptionistSelected($(this).val());
                    break;

                case 'playback' :
                    $('.action-call').empty();
                    playbackSelected($(this).val());
                    break;

                case 'voicemail' :
                    $('.action-call').empty();
                    voicemailSelected($(this).val());
                    break;

                default :
                    $('.action-call').empty();
                    break;

            }

        })

        function groupOptions(){
            $('.destination').append(`<option value=""> &dash; &dash; &dash; Select Destination &dash; &dash; &dash; </option>`);
            groups.forEach(function(value, index){

                $('.destination').append(`<option value="${value.id}" data-type="group" > ${value.name + " - " + value.number } </option>`); 

            });
        }

        function numberOptions(){
            $('.destination').append(`<option value=""> &dash; &dash; &dash; Select Destination &dash; &dash; &dash; </option>`);
            numbers.forEach(function(value, index){

                $('.destination').append(`<option value="${value.id}" data-type="number" > ${ value.number } </option>`); 

            });
        }

        function extenOptions(){
            $('.destination').append(`<option value=""> &dash; &dash; &dash; Select Destination &dash; &dash; &dash; </option>`);
            extens.forEach(function(value, index){

                $('.destination').append(`<option value="${value.id}" data-type="extension" > ${value.name + " - " + value.number } </option>`); 

            });
        }

        function receptionistOptions(){
            $('.destination').append(`<option value=""> &dash; &dash; &dash; Select Destination &dash; &dash; &dash; </option>`);
            receptionists.forEach(function(value, index){

                $('.destination').append(`<option value="${value.id}" data-type="receptionist" > ${value.name } </option>`); 

            });
        }

        function playbackOptions(){
            $('.destination').append(`<option value=""> &dash; &dash; &dash; Select Destination &dash; &dash; &dash; </option>`);
            playbacks.forEach(function(value, index){

                $('.destination').append(`<option value="${value.id}" data-type="playback" > ${value.title } </option>`); 

            });
        }

        function voicemailOptions(){
            $('.destination').append(`<option value=""> &dash; &dash; &dash; Select Destination &dash; &dash; &dash; </option>`);
            
            $('.destination').append(`<option value="1" data-type="voicemail" > Voicemail Box </option>`); 

        }


        function numberSelected(current){

            $element = numbers.filter(function(entry) {
                return entry.id === parseInt(current);
                
            });
            if($element.length > 0){

                $('.module').val('Number');
                $('.destination_value').val($element[0].number);
            
            }
        }


        function extenSelected(current){

            $element = extens.filter(function(entry) {
                return entry.id === parseInt(current);
                
            });
            if($element.length > 0){
                $('.module').val('Extension');
                $('.destination_value').val($element[0].number);
            }
        }


        function playbackSelected(current){

            $element = playbacks.filter(function(entry) {
                return entry.id === parseInt(current);
                
            });
            if($element.length > 0){
                $('.module').val('Playback');
                $('.destination_value').val($element[0].title);
            }
        }


        function groupSelected(current){

            $element = groups.filter(function(entry) {
                return entry.id === parseInt(current);
                
            });

            let destinations = "";
            if($element.length > 0){
                $element[0].members.forEach( function(number, index){
                    destinations += number.number;
                    if($element[0].members.length - 1 > index ){

                        destinations +=" | ";

                    }
                });
                $('.module').val('GroupCall');
                $('.destination_value').val($element[0].name);

            }
        }

        function receptionistSelected(current){

            $element = receptionists.filter(function(entry) {
                return entry.id === parseInt(current);
                
            });
            if($element.length > 0){
                
                $('.module').val('VirtualReceptionist');
                $('.destination_value').val($element[0].name);
            }
        }


        function voicemailSelected(current){

            $('.module').val('Voicemail');
            $('.destination_value').val('Voicemail');
        }



    </script>


@endsection

@section('extra-css')

    <style>
        
   
        

    </style>

@endsection