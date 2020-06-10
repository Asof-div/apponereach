@extends('layouts.tenant_sidebar')

@section('title')
    
    CALL FLOW

@endsection

@section('breadcrumb')

    <li><a href="{{ url($tenant->domain.'/dashboard') }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.media-service.index', [$tenant->domain]) }}"> Media Services </a></li>
    <li><a href="{{ route('tenant.media-service.call-flow.index', [$tenant->domain]) }}"> Call Flow </a></li>
    <li class="active"> New Call Flow </li>

@endsection

@section('content')
    
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading"> 
                <div class="panel-title p-b-10 p-t-10">
                    <span class="h3"> 
                        Add Call Flow
                    </span> 
                    <span class="pull-right m-r-10">
                        <a href="{{ route('tenant.media-service.call-flow.index', [$tenant->domain]) }}" class="btn btn-lg btn-outline-default"> Call Flow </a>
                    </span>
                </div>
                <hr class="horizonal-line-thick">
            </div>

            <div class="panel-body" style="min-height: 400px;">

                <div class="col-md-12 col-sm-12 col-xs-12 clearfix">

                    @include('app.tenant.media-services.call_flow.partials.form')

                </div>

            </div>



        </div>
    </div>
        
@endsection


@section('extra-script')
    
    <script type="text/javascript" src="{{ URL::to('js/rSlider.js') }}"></script>
    <script type="text/javascript">
        $mn_list = $('.sidebar ul.nav > li.nav-media-services');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-call-flow').addClass('active');
        var count = 1;

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
                $('.action-call').append(`<li class="call-flow-component f-s-13"> Dial CUG Number : ${$element[0].number} </li>`);
                $('.destination_value').val($element[0].number);
            
            }
        }


        function extenSelected(current){

            $element = extens.filter(function(entry) {
                return entry.id === parseInt(current);
                
            });
            if($element.length > 0){
                $('.module').val('Extension');
                $('.action-call').append(`<li class="call-flow-component f-s-13"> Dial Extension : ${$element[0].name} - ${$element[0].number} </li>`);
                $('.destination_value').val($element[0].number);
            }
        }


        function playbackSelected(current){

            $element = playbacks.filter(function(entry) {
                return entry.id === parseInt(current);
                
            });
            if($element.length > 0){
                $('.module').val('Playback');
                $('.action-call').append(`<li class="call-flow-component f-s-13"> Play a message : ${$element[0].title}  </li>`);
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
                $('.action-call').append(`<li class="call-flow-component f-s-13">Dial Group : ${$element[0].name} - ${destinations} </li>`);
                $('.destination_value').val($element[0].name);

            }
        }

        function receptionistSelected(current){

            $element = receptionists.filter(function(entry) {
                return entry.id === parseInt(current);
                
            });
            if($element.length > 0){
                
                $('.module').val('VirtualReceptionist');
                $('.action-call').append(`<li class="call-flow-component f-s-13">Send To VirtualReceptionist : ${$element[0].name} </li>`);
                $('.destination_value').val($element[0].name);
                $element[0].menus.forEach(function(number){
                    $('.action-call').append(`<li class="call-flow-component f-s-13"> press ${number.key} for ${number.value} </li>`);
                });
            }
        }


        function voicemailSelected(current){

            $('.module').val('Voicemail');
            $('.action-call').append(`<li class="call-flow-component f-s-13"> Leave Voice Message </li>`);
            $('.destination_value').val('Voicemail');
        }


    </script>


@endsection


@section('extra-css')

    <link type="text/css" rel="stylesheet" href="{{ URL::to('css/rSlider.css') }}" />

    <style type="text/css">
        
        .call-flow-component{
            padding: 15px;
            padding-left: 40px;
            margin: 10px;
        }

        .phone-route{
            padding: 7px;
            margin: 7px;
            border-radius: 4px;
            background: #f3f3f4;
            display: inline-block;

        }
        .nav-stacked > li {
            border: 2px #51bb8d solid;
            border-radius: 4px;
            background-color: #f2f2f2;
        }

    </style>

@endsection