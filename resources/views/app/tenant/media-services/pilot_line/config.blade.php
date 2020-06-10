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

                // let icon = getFontAwesomeHtml(route.attr('data-destination_type'));
                let tr = `<tr class='f-s-15'> <td> ${tag.val()} <input type="hidden" name="titles[]" value="${tag.val()}" /> </td>  
                        <td> ${timer.attr('data-title')} <input type="hidden" name="timers[]" value="${timer.val()}" /> </td>
                        <td> ${timer.attr('data-start')} - ${timer.attr('data-end')} </td>
                        <td> ${timer.attr('data-strategy')} </td>
                        <td> ${route.attr('data-title')} <input type="hidden" name="routes[]" value="${route.val()}" /> </td>
                        <td> ${route.attr('data-voicemail')} </td>
                        <td> ${route.attr('data-record')} </td>
                        <td> ${route.attr('data-destination_type')} </td>
                        <td> ${route.attr('data-destination')} </td>
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



        function getDestinationTypeLabel(name){
            let label = '';
            switch(name){
                case 'extension' :
                    label = 'Ring On An Extension';
                    break;

                case 'number' :
                    label = 'Ring On A Number';
                    break;

                case 'group' :
                    label = 'Ring A Group';
                    break;

                case 'receptionist' :
                    label = 'Direct To Virtual Receptionist';
                    break;

                case 'playback' :
                    label = 'Play A Custom Message';
                    break;

                case 'voicemail' :
                    label = 'Send To Voicemail';
                    break;

                case 'conference' :
                    label = 'Join A Private Conference';
                    break;

                default :
                    label = '';
                    break;

            }
            return label;

        }


        function isEmpty(val){
            return (val === undefined || val == null || val.length <= 0) ? true : false;
        }

        $('body').on('click', '.destination-type-selector>li', function(e){

            e.preventDefault();
            $('.destination-type-box').addClass('hidden');
            $('.destination-box').removeClass('hidden');
            $('.destinations').addClass('hidden');
            let destType = $(this).attr('data-destination');
            switch(destType){
                case 'extension' :
                    $('.extension-destinations').removeClass('hidden');
                    break;
                case 'number' :
                    $('.number-destinations').removeClass('hidden');
                    break;
                case 'group' :
                    $('.group-destinations').removeClass('hidden');
                    break;
                case 'receptionist' :
                    $('.receptionist-destinations').removeClass('hidden');
                    break;
                case 'playback' :
                    $('.playback-destinations').removeClass('hidden');
                    break;
                case 'voicemail' :
                    $('.voicemail-destinations').removeClass('hidden');
                    break;
                default :
                    $('.destination-type-box').removeClass('hidden');
                    $('.destination-box').addClass('hidden');
                    break;

            }            

        });

        $('body').on('click', '.btn-back-destination-type', function(e){
            e.preventDefault();
            $('.destination-box').addClass('hidden');
            $('.destinations').addClass('hidden');
            $('.destination-type-box').removeClass('hidden');
        });


        interact('.dragable')
          .on('doubletap', function(event){
            event.preventDefault();
            let button = event.currentTarget;
            let label = getDestinationTypeLabel(button.getAttribute('data-type'));
            $('.double-tap-dest').text(button.textContent);
            $('.double-tap-dest-type').text(label);
            $('.dragable').removeClass('switch-bg');
            button.classList.add('switch-bg');
            $('#destination').val(button.getAttribute('data-destination_id'));
            $('#destination_type').val(button.getAttribute('data-type'));            
            $('#destination_display').val(button.textContent);            
            setTimeout(function(){ 
                button.classList.remove('switch-bg');
            }, 1000);
            
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

        #destination-dropzone {
          height: 200px;

          touch-action: none;
        }

        .dropzone {
          background-color: #ccc;
          border: dashed 4px transparent;
          border-radius: 4px;
          padding: 10px;
          width: 80%;
          transition: background-color 0.3s;
        }

        .drop-active {
          border-color: #aaa;
        }

        .drop-target {
          background-color: #29e;
          border-color: #fff;
          border-style: solid;
        }

        .dragable {
            list-style-type: none;
            display: inline-block;
            min-width: 40px;
            height: 40px;
            color: #fff;
            background-color: #29e;
            border: solid 2px #fff;
            padding: 10px;
            margin: 5px;
            -webkit-transform: translate(0px, 0px);
            transform: translate(0px, 0px);

            transition: background-color 0.3s;
            cursor: pointer;
            touch-action: none;
        }
        .dragable.switch-bg {
            border: 2px solid #006848;
            color: #006848;
            animation: glow 1.5s ease infinite;

        }

        .drag-drop.can-drop {
          color: #000;
          background-color: #4e4;
        }

        .dragged-element-revert{
            position: relative;
        }


        .double-tap-destination {
            min-width: 40px;
            height: 40px;
            padding: 10px;
            text-align: center;
            font-size: 16px;
            color: #006848 !important;
            overflow-wrap: break-word;
            /*animation: bounce 1.5s ease infinite;*/
            animation: glow 1.5s ease infinite;
        }

        @keyframes glow {
          50% {
            background: rgba(255,255,255,.6);
          }
        }

        @keyframes bounce {
          50% {
            transform:
              scale(.98)
              translateY(-5%);
          }
        }

        @keyframes grow {
          from { transform: scale(0); }
          to {
            opacity: 0;
            transform: scale(1);
          }
        }
        
        ul.destination-type-selector, ul{
            padding: 0;
            margin: 0;
        }
        ul.destination-type-selector li{
            list-style-type: none;
            display: inline-block;
            min-width: 80px;
            height: 50px;
            padding: 10px;
            margin: 5px;
            font-size: 14px;
            overflow-wrap: break-word;
            background-color: #006848;
            color: #fff;
            border: 2px solid #b4c404;
            cursor: pointer;
            border-radius: 5px;
   
        }
        ul.destination-type-selector li:hover{
            border: 2px solid #006848;
            background-color: #b4c404;
            color: #fff;   
        }


    </style>

@endsection