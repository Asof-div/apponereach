@extends('layouts.tenant_sidebar')

@section('title')
    
    VIRTUAL RECEPTIONIST

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.media-service.index', [$tenant->domain]) }}"> Media Services </a></li>
    <li><a href="{{ route('tenant.media-service.virtual-receptionist.index', [$tenant->domain]) }}"> Virtual Receptionist </a></li>
    <li class="active"> New Virtual Receptionist </li>

@endsection

@section('content')

   
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading"> 
                <div class="panel-title p-t-10">
                    <span class="h3"> Create Virtual Receptionist </span> 
                    <span class="pull-right m-r-10">
                        <a href="{{ route('tenant.media-service.virtual-receptionist.index', [$tenant->domain]) }}" class="btn btn-lg btn-outline-default"> Virtual Receptionist <span class="text-primary"> ({{ $receptionists->count() }}) </span> </a>
                    </span>
                </div>
                <hr class="horizonal-line-thick">
            </div>

            <div class="panel-body" style="min-height: 400px;">

                <div class="col-md-12 col-sm-12 col-xs-12">

                    @include('app.tenant.media-services.virtual-receptionist.partials.form')

                </div>

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

        $('body').on('click', '.add-route', function(e){

            let table_body = $('tbody.ivr-menu');

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
                $('.ivr-menu-tts>span').html( $(this).find(":selected").attr('data-text') );
                $('.ivr-menu-tts>textarea').html( $(this).find(":selected").attr('data-text') );
                $('#ivr').val('yes');
                $('.ivr-menu-sound').addClass('hidden');
                $('.ivr-sound-path').val("");
                $('.ivr-menu-tts').removeClass('hidden');
            }
            else if('s' == $(this).val().substr(0,1)){
                $('.ivr-menu-tts').addClass('hidden');
                $('.ivr-menu-tts>textarea').addClass('hidden');
                $('#ivr').val('no');
                $('.ivr-menu-tts>span').html('');
                $('.ivr-menu-tts>textarea').html('');
                $('.ivr-menu-sound').html(`<audio src="${ storage_path + "/" + $(this).find(":selected").attr('data-text')}" controls="controls"> </audio> `);
                $('.ivr-menu-sound').removeClass('hidden');
                $('.ivr-sound-path').val($(this).find(":selected").attr('data-text'));
            }else{
                $('.ivr-menu-tts').addClass('hidden');
                $('.ivr-menu-sound').addClass('hidden');
                $('.ivr-menu-tts>span').html('');
                $('.ivr-menu-tts>textarea').html('');
                $('.ivr-sound-path').val("");
                
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



        $('body').on('submit', '#virtual_receptionist_form',  function(event){

            $('#system_overlay').removeClass('hidden');
            event.preventDefault();
            $("#destination").val('');
            if($('tbody.ivr-menu>tr').length > 0)
                $("#destination").val('12');

            formData = new FormData(document.getElementById('virtual_receptionist_form'));

            url = "{{ route('tenant.media-service.virtual-receptionist.store', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                document.getElementById('virtual_receptionist_form').reset();
                let url = "{{ route('tenant.media-service.virtual-receptionist.index', [$tenant->domain]) }}";
                setTimeout(function(){ 
                    window.location = url;
                }, 6000); 
                
            }
            let failed = function(data){

            }

            ajaxCall(url, formData, success, failed);  


        });

        

    
    </script>


@endsection

@section('extra-css')
        
    <style>

    

    </style>

@endsection