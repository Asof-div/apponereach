@extends('layouts.tenant_sidebar')

@section('title')
    
    TIME SCHEDULER

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.media-service.index', [$tenant->domain]) }}"> Media Services </a></li>
    <li><a href="{{ route('tenant.media-service.timer.index', [$tenant->domain]) }}"> Timer </a></li>
    <li class="active"> New Timer </li>

@endsection

@section('content')
    
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading"> 
                <div class="panel-title p-b-10 p-t-10">
                    <span class="h3"> 
                        Add Time Schedule
                    </span> 
                    <span class="pull-right m-r-10">
                        <a href="{{ route('tenant.media-service.timer.index', [$tenant->domain]) }}" class="btn btn-lg btn-outline-default"> Timer </a>
                    </span>
                </div>
                <hr class="horizonal-line-thick">
            </div>

            <div class="panel-body" style="min-height: 400px;">

                <div class="col-md-12 col-sm-12 col-xs-12">

                    @include('app.tenant.media-services.timer.partials.form')

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
        $mn_list.find('.sub-menu > .sub-menu-timer').addClass('active');
        

        $(function(){
        

            $('.timepicker .time').timepicker({ 'timeFormat': 'H:i', 'step' : 30, 'showDuration' : true });

            $('.timepicker').datepair();

            $('.hour-strategy .timebox > .time').attr('disabled', true);
           

        }); 

        $("body").on('click', '.day_of_week>input', function () {

            let days = 0;
            $('.day_of_week>input[type=checkbox]:checked').each(function(){
                
                days = days + parseInt( $(this).val() );

            });

            $('#weekdays').val(days);

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

                $('.strategy').addClass('hide');
                $('.hour-strategy').removeClass('hide');

                $('.hour-strategy .timebox > .start').val('00:00');
                $('.hour-strategy .timebox > .end').val('24:00');
                $('.hour-strategy .timebox > .time').attr('disabled', true);
                $('#start_time').val('00:00');
                $('#end_time').val('24:00');
                $('#weekdays').val('127');

            }else if(data == 'custom') {

                $('.day_of_week>input[value=1]').prop('checked', false);
                $('.day_of_week>input[value=2]').prop('checked', true);
                $('.day_of_week>input[value=4]').prop('checked', true);
                $('.day_of_week>input[value=8]').prop('checked', true);
                $('.day_of_week>input[value=16]').prop('checked', true);
                $('.day_of_week>input[value=32]').prop('checked', true);
                $('.day_of_week>input[value=64]').prop('checked', false);
                $('.day_of_week>input').attr('disabled', false);
                $('.hour-strategy .timebox > .time').attr('disabled', false);
                

                $('.strategy').addClass('hide');
                $('.hour-strategy').removeClass('hide');

                $('.hour-strategy .timebox .start').val('08:00');
                $('.hour-strategy .timebox .end').val('17:00');
                $('#start_time').val('08:00');
                $('#end_time').val('17:00');
                $('#start_mon').val('');
                $('#start_day').val('');
                $('#end_day').val('');
                $('#custom_day').val('');
                $('#weekdays').val('62');




            }else if(data == 'date'){

                $('.strategy').addClass('hide');
                $('.date-strategy').removeClass('hide');

                $('.date-strategy .timebox .start').val('08:00');
                $('.date-strategy .timebox .end').val('17:00');
                $('#start_time').val('08:00');
                $('#end_time').val('17:00');
                $('#start_mon').val('JAN');
                $('#start_day').val('1');
                $('#end_day').val('');
                $('#custom_day').val('');
                $('#weekdays').val('127');

            }else if(data == 'custom_date'){

                $('.strategy').addClass('hide');
                $('.custom-date-strategy').removeClass('hide');

                $('.custom-date-strategy .timebox .start').val('08:00');
                $('.custom-date-strategy .timebox .end').val('17:00');
                $('#start_time').val('08:00');
                $('#end_time').val('17:00');
                $('#start_mon').val('JAN');
                $('#start_day').val('1');
                $('#end_day').val('');
                $('#custom_day').val('First');
                $('#weekdays').val('2');
            }else if(data == 'range'){

                $('.strategy').addClass('hide');
                $('.date-range-strategy').removeClass('hide');

                $('.date-range-strategy .timebox .start').val('08:00');
                $('.date-range-strategy .timebox .end').val('17:00');
                $('#start_time').val('08:00');
                $('#end_time').val('17:00');
                $('#start_mon').val('JAN');
                $('#start_day').val('1');
                $('#end_day').val('29');
                $('#custom_day').val('');
                $('#weekdays').val('127');
            }else {

                $('.strategy').addClass('hide');
                $('#start_time').val('');
                $('#end_time').val('');

            }           
            
        });


        $('body').on('change', '.timebox .start', function(e){

            $('#start_time').val($(this).val());

        });

        $('body').on('change', '.timebox .end', function(e){

            $('#end_time').val($(this).val());

        });

        $('body').on('click', '.start_mon', function(e){

            $('#start_mon').val($(this).val());

        });


        $('body').on('click', '.start_day', function(e){

            $('#start_day').val($(this).val());

        });


        $('body').on('click', '.end_day', function(e){

            $('#end_day').val($(this).val());

        });

        $('body').on('click', '.weekday', function(e){

            $('#weekdays').val($(this).val());

        });

        $('body').on('click', '.custom_day', function(e){

            $('#custom_day').val($(this).val());

        });

    </script>


@endsection


@section('extra-css')

    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery.timepicker.min.css') }}">

    <style type="text/css">
        
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
        
        .rs-scale{
            width: 110%;
        }

        .rs-container{
            width: 95% !important;
        }

        .nav-stacked > li {
            border: 2px #51bb8d solid;
            border-radius: 4px;
            background-color: #f2f2f2;
        }

    </style>

@endsection