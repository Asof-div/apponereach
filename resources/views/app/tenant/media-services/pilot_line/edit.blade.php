@extends('layouts.tenant_sidebar')

@section('title')
    
    DID SETTINGS ({{ $pilot_line->number }})

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.media-service.index', [$tenant->domain]) }}"> Media Services </a></li>
    <li><a href="{{ route('tenant.media-service.pilot-line.index', [$tenant->domain]) }}"> Pilot Numbers </a></li>
    <li class="active"> Pilot Number Setting </li>

@endsection

@section('content')


    <div class="col-md-12 col-sm-12 col-xs-12 p-0">
        <div class="panel panel-default">

            <div class="panel-body" style="min-height: 400px;">
                <div class="clearfix">
                    <a class="btn btn-default" href="{{ route('tenant.media-service.pilot-line.index', [$tenant->domain]) }}"> <i class="fa fa-list"></i> Pilot Lines </a>
                    <a class="btn btn-success" href="{{ route('tenant.media-service.pilot-line.config', [$tenant->domain, $pilot_line->number]) }}" > <i class="fa fa-sitemap"></i> Call Routing </a>
                </div>
                <hr class="horizonal-line-thick" />

                <div class="col-md-12 col-sm-12 col-xs-12 p-t-15 bg-silver">

                    @include('app.tenant.media-services.pilot_line.partials.edit_form')

                </div>

            </div>



        </div>
    </div>


@endsection


@section('extra-script')
    
    <script type="text/javascript" src="{{ asset('js/jquery.timepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.datepair.min.js') }}"></script>

    <script type="text/javascript">

        $mn_list = $('.sidebar ul.nav > li.nav-media-services');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-pilot-line').addClass('active');

        let pilot = <?= json_encode($pilot_line) ?>;
        $(function(){
        

            $('.timepicker .time').timepicker({ 'timeFormat': 'H:i', 'step' : 30, 'showDuration' : true });

            $('.timepicker').datepair();

            $('.hour-strategy .timebox > .time').attr('disabled', true);

            $('input[value='+pilot.recording_period+'].selected-period').click();
            setTimeout(function(){ 
                $('.day_of_week>input').prop('checked', false);
                
                console.log('timer '+ parseInt(pilot.recording_days).toString(2));

                parseInt(pilot.recording_days).toString(2).split('').reverse().forEach(function(v,i){
                    if(parseInt(v) == 1){
                        
                        $('.day_of_week>input[value='+Math.pow(2,i)+']').prop('checked', true); 
                    }
                });

                }, 1000); 

        }); 


        $('body').on('submit', '#voicemail_settings_form',  function(event){
            event.preventDefault();
            
            formData = new FormData(document.getElementById('voicemail_settings_form'));

            url = "{{ route('tenant.media-service.pilot-line.voicemail', [$tenant->domain]) }}"; 

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
    

        $('body').on('submit', '#recording_settings_form',  function(event){
            event.preventDefault();
            
            formData = new FormData(document.getElementById('recording_settings_form'));

            url = "{{ route('tenant.media-service.pilot-line.recording', [$tenant->domain]) }}"; 

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

        $('body').on('click', '.selected-period', function(e){

            let data = $(this).val();
            if(data == 'all') {
                $('.day_of_week>input[value=1]').prop('checked', true);
                $('.day_of_week>input[value=2]').prop('checked', true);
                $('.day_of_week>input[value=4]').prop('checked', true);
                $('.day_of_week>input[value=8]').prop('checked', true);
                $('.day_of_week>input[value=16]').prop('checked', true);
                $('.day_of_week>input[value=32]').prop('checked', true);
                $('.day_of_week>input[value=64]').prop('checked', true);
                $('.day_of_week>input').attr('disabled', true);


                $('.hour-strategy .timebox > .start').val('00:00');
                $('.hour-strategy .timebox > .end').val('24:00');
                $('.hour-strategy .timebox > .time').attr('disabled', true);
                $('#start_time').val('00:00');
                $('#end_time').val('24:00');
                $('#weekdays').val('127');

            }else if(data == 'custom') {

                $('.day_of_week>input').attr('disabled', false);
                $('.hour-strategy .timebox > .time').attr('disabled', false);
                

                $('.hour-strategy .timebox .start').val(pilot.recording_start);
                $('.hour-strategy .timebox .end').val(pilot.recording_end);
                $('#start_time').val(pilot.recording_start);
                $('#end_time').val(pilot.recording_end);
                $('#weekdays').val(pilot.recording_days);


            }else {

                $('.strategy').addClass('hide');
                $('#start_time').val('');
                $('#end_time').val('');

            }           
            
        });

        $("body").on('click', '.day_of_week>input', function () {

            let days = 0;
            $('.day_of_week>input[type=checkbox]:checked').each(function(){
                
                days = days + parseInt( $(this).val() );

            });

            $('#weekdays').val(days);

        });

        $('body').on('change', '.timebox .start', function(e){

            $('#start_time').val($(this).val());

        });

        $('body').on('change', '.timebox .end', function(e){

            $('#end_time').val($(this).val());

        });


    </script>


@endsection

@section('extra-css')

    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery.timepicker.min.css') }}">

    <style>

        .day_of_week:nth-child(even){

            background : #f2f2d2;
            padding: 10px;
            padding-left: 30px;
            margin: 10px 10px 10px 0;
            width: 70px; 
        }
        .day_of_week:nth-child(odd){

            background : #e2e2e2;
            padding: 10px;
            padding-left: 30px;
            margin: 10px 10px 10px 0;
            width: 70px; 
        }

        .day_of_week_checked{

            background: #51bb8d !important;

        }
     

    </style>

@endsection