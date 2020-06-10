@extends('layouts.tenant_sidebar')

@section('title')
    
    PILOT NUMBER ROUTING ({{ $pilot_line->number }})

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.media-service.index', [$tenant->domain]) }}"> Media Services </a></li>
    <li><a href="{{ route('tenant.media-service.pilot-line.index', [$tenant->domain]) }}"> Pilot Numbers </a></li>
    <li class="active"> Pilot Number Routing </li>

@endsection

@section('content')


    <div class="col-md-12 col-sm-12 col-xs-12 p-0">

        <div class="panel panel-default">

            <div class="panel-body" style="min-height: 400px;">
                <div class="clearfix">
                    <a class="btn btn-default" href="{{ route('tenant.media-service.pilot-line.index', [$tenant->domain]) }}"> <i class="fa fa-list"></i> Pilot Lines </a>
                    <a class="btn btn-success" href="{{ route('tenant.media-service.pilot-line.edit', [$tenant->domain, $pilot_line->number]) }}" > <i class="fa fa-edit"></i> Edit Setting </a>
                </div>

                <hr class="horizonal-line-thick" />

                <div class="col-md-12 col-sm-12 col-xs-12 p-t-15 bg-silver">

                    @include('app.tenant.media-services.pilot_line.partials.details')

                </div>

            </div>



        </div>
    </div>


    @include('app.tenant.media-services.pilot_line.modal.default')
@endsection


@section('extra-script')
    
    <script type="text/javascript" src="{{ asset('js/jquery.timepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.datepair.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/interactjs@1.3.4/dist/interact.min.js"></script>
    <script type="text/javascript">

        $mn_list = $('.sidebar ul.nav > li.nav-media-services');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-pilot-line').addClass('active');


        var _token = "{{csrf_token()}}";
        let numbers = <?= json_encode($numbers); ?>;
        let extens = <?= json_encode($extens); ?>;
        let groups = <?= json_encode($groups); ?>;
        let receptionists = <?= json_encode($receptionists); ?>;
        let playbacks = <?= json_encode($sounds); ?>;
        // let voicemails ;

        $('.reorder-list').sortable();

        $('body').on('click', '.default_toggle', function(e){

            
            if($(this).prop("checked") == true){

                $('.default_dest_cont').fadeIn();
                $('.default_dest_cont').removeClass('hide');

            }
            else{
                $('.default_dest_cont').fadeOut();
            }


        });

        $('body').on('click', '.add-auto-attendant', function(e){

            let tag = $('.auto-attendant-tag');
            let timer = $('.auto-attendant-timer').find(":selected");
            let route = $('.auto-attendant-route').find(":selected");
            if( !isEmpty(tag.val()) && !isEmpty(timer.val()) && !isEmpty(route.val()) ){

                let icon = getFontAwesomeHtml(route.attr('data-destination_type'));
                let tr = `<tr class='f-s-15'> <td> ${tag.val()} <input type="hidden" name="titles[]" value="${tag.val()}" /> </td>  
                        <td> ${timer.attr('data-title')} <input type="hidden" name="timers[]" value="${timer.val()}" /> </td>
                        <td> ${timer.attr('data-start')} - ${timer.attr('data-end')} </td>
                        <td> ${timer.attr('data-strategy')} </td>
                        <td> ${route.attr('data-title')} <input type="hidden" name="routes[]" value="${route.val()}" /> </td>
                        <td> ${route.attr('data-voicemail')} </td>
                        <td> ${route.attr('data-record')} </td>
                        <td> ${route.attr('data-destination_type')} </td>
                        <td> ${icon} ${route.attr('data-destination')} </td>
                        <td> <button type="button" class="btn btn-xs btn-danger remove-auto-attendant"> <i class="fa fa-trash"> </i> </button> </td>

                </tr>`;

                $('.reorder-list').append(tr);

                $('#config_status').val($('.reorder-list>tr').length );

            }else{
                printErrorMsg(['You Can not Add Auto Attendant Without Selecting A Time And A Call Flow. ']);
                $.gritter.add({
                    title: '',
                    text: "You Can not Add Auto Attendant Without Selecting A Time And A Call Flow.",
                    sticky: false,
                    time: '6000'
                });                
            }



        });

        $('body').on('click', '.remove-auto-attendant', function(e){

            $(this).closest('tr').remove();
            
            $('#config_status').val($('.reorder-list tr').length );

        });

        $('body').on('submit', '#pilot_line_destination_form',  function(event){
            event.preventDefault();
            
            formData = new FormData(document.getElementById('pilot_line_destination_form'));

            url = "{{ route('tenant.media-service.pilot-line.config.default', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location.reload(); 
                }, 7000);  

                $.gritter.add({
                    title: 'Default Destination',
                    text: data.success,
                    sticky: false,
                    time: '8000'
                });
                
            }
            let failed = function(data){

            }

            ajaxCall(url, formData, success, failed); 

        }); 

        $('body').on('submit', '#auto_attendant_reorder_form',  function(event){
            event.preventDefault();
            
            formData = new FormData(document.getElementById('auto_attendant_reorder_form'));

            url = "{{ route('tenant.media-service.pilot-line.config.reorder_attendant', [$tenant->domain, $pilot_line->number]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location.reload(); 
                }, 5000);  

                $.gritter.add({
                    title: 'Auto Attendant',
                    text: data.success,
                    sticky: false,
                    time: '8000'
                });
                
            }
            let failed = function(data){

            }

            ajaxCall(url, formData, success, failed); 

        }); 


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
                    $('.playback-message').html(`<div class='text-center'><audio src="${$(this).find(":selected").attr('data-text')}" controls="controls"> </audio> </div>`);
                    $('.sound-path').val( $(this).find(":selected").attr('data-path') );

                    break;
                    $('.playback-message').html(``);
                    $('.sound-path').val("");

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
                    break;

                case 'voicemail' :
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
            
        }

        function numberSelected(current){

            $element = numbers.filter(function(entry) {
                return entry.id === parseInt(current);
                
            });
            if($element.length > 0){

                $('.module').val('Number');
                $('.action-call').append(`<li class="f-s-18">
                    <div class="text-center"> <i class="fa fa-long-arrow-down"></i> </div>
                    <div class="call-flow-component m-5 p-10 f-s-18">
                    <div class="text-center"> <i class="fa fa-user"></i> &nbsp; <span class="f-s-15">Dial CUG Number : ${$element[0].number} </span> </div>
                    <table class='table table-striped'> 
                     <tr class="text-center text-primary m-5"> <td> <i class="fa fa-phone"></i> &nbsp; ${$element[0].number} </td> </tr> 
                    </table>
                    </li>`);
                $('.destination_value').val($element[0].number);
            
            }
        }


        function extenSelected(current){

            $element = extens.filter(function(entry) {
                return entry.id === parseInt(current);
                
            });
            if($element.length > 0){
                $('.module').val('Extension');
                $('.action-call').append(`<li class="f-s-18">
                    <div class="text-center"> <i class="fa fa-long-arrow-down"></i> </div>
                    <div class="call-flow-component m-5 p-10 f-s-18">
                    <div class="text-center"> <i class="fa fa-user"></i> &nbsp; <span class="f-s-15">Dial Extension : ${$element[0].name} </span> </div>
                     <div class="text-center text-primary m-5"> <i class="fa fa-tty"></i> &nbsp; ${$element[0].number} </div> 
                    </li>`);
                $('.destination_value').val($element[0].number);
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
                $('.action-call').append(`<li class="f-s-18">
                    <div class="text-center"> <i class="fa fa-long-arrow-down"></i> </div>
                    <div class="call-flow-component m-5 p-10 f-s-18">
                    <div class="text-center"> <i class="fa fa-users"></i> &nbsp; <span class="f-s-15">Dial Group : ${$element[0].name} </span> </div>
                     <div class="text-center text-primary m-5"> <i class="fa fa-users"></i> &nbsp; ${destinations} </div> 
                    </li>`);
                $('.destination_value').val($element[0].name);

            }
        }

        function receptionistSelected(current){

            $element = receptionists.filter(function(entry) {
                return entry.id === parseInt(current);
                
            });
            if($element.length > 0){
                
                let destination = "";
                $element[0].menus.forEach(function(number){
                    let icon = getFontAwesomeHtml(number.action);
                                
                    destination += `<div class="text-justify text-primary"> PRESS ${number.key} FOR ${icon} &nbsp; ${number.value} </div>`;
                });
                $('.module').val('VirtualReceptionist');
                $('.action-call').append(`<li class="f-s-18">
                    <div class="text-center"> <i class="fa fa-long-arrow-down fa-2x"></i> </div>
                    <div class="call-flow-component m-5 p-10 f-s-18">
                    <div class="text-center"> <i class="fa fa-th-large"></i> &nbsp; <span class="f-s-15">Send to Virtual Receptionist : ${$element[0].name} </span> </div>
                      ${destination}
                 </li>`);
                $('.destination_value').val($element[0].name);
                
            }
        }


        function getFontAwesomeHtml(type){
            let icon = "";
            switch (type.toLowerCase()) {
                case 'dial_sip_profile': case 'extension':
                        icon = `<i class='fa fa-tty '> </i>`;
                    break;
                case 'dial_external' : case 'number':
                        icon = `<i class='fa fa-phone'> </i>`; 
                    break;
                case 'dial_group' : case 'group':
                        icon = `<i class='fa fa-users'> </i>`;
                    break;
                case 'sub_menu': case 'receptionist': case 'ivr' :
                        icon = `<i class='fa fa-th-large'> </i>`;
                    break;
                case 'voicemail':
                        icon = `<i class='fa fa-inbox'> </i>`;
                    break;
                case 'playback':
                        icon = `<i class='fa fa-volume-up'> </i>`;
                    break;
                
                default:
                        icon = '';
                    break;

            }
            return icon;
        }

        function isEmpty(val){
            return (val === undefined || val == null || val.length <= 0) ? true : false;
        }


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

        .call-flow-component{
            /*background-color: #b6c2c9;*/
            /*color: #333;*/
        }
        .call-flow-component > div{
            border-bottom: 1px solid #fff;
        }
    </style>

@endsection