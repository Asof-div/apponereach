@extends('layouts.tenant_sidebar')

@section('title')
    
    TASK MANAGEMENT

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.crm.index', $tenant->domain) }}"> CRM </a></li>
    <li><a href="{{ route('tenant.crm.task.index', $tenant->domain) }}"> TASK </a></li>
    <li class="active"> Create Task </li>

@endsection

@section('content')

   
    <div class="col-md-12 col-sm-12 col-xs-12 p-0">
        <div class="panel panel-default">
            <div class="panel-heading"> 
            
                <div class="panel-title"> 
                    <span class="h3"> New Task &nbsp; <span class="text-primary">  </span> </span> 
                    <span class="pull-right m-r-10">
                        <a href="{{ route('tenant.crm.task.index', [$tenant->domain]) }}" class="btn btn-lg btn-outline-default"> <i class="fa fa-list"></i> List Task </a>
                    </span>
                </div> 
                <hr class="horizonal-line-thick" />
            </div>

            <div class="panel-body" style="min-height: 400px;">

                <div class="col-md-12 col-sm-12 col-xs-12 bg-silver" >

                    @include('app.tenant.crm.task.partials.form')

                </div>

            </div>
        </div>
    </div>

        



@endsection


@section('extra-script')

    <script type="text/javascript" src="{{ asset('js/wickedpicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>
    <script>
       
        $mn_list = $('.sidebar ul.nav > li.nav-crm');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-task').addClass('active');


        $(function () {
            today = new Date();
            $('.datepicker').datepicker({
                format: 'yyyy-m-d',
                rangeLow: today.getFullYear() + today.getMonth() + today.getDay(),
                defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 3,
                minDate: today,
                onSelect: function(selectedDate) {
                    let option = this.id == "from" ? "minDate" : "maxDate",
                        instance = $(this).data("datepicker"),
                        date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
                    dates.not(this).datepicker("option", option, date);
                }
            });
            $('.timepicker').wickedpicker({twentyFour: true});
            $("input[name='assignee']").click();

            $('[name="repeat_task"]').on('change', function() {
                if ($(this).is(':checked')) {
                    $('.schedule-repeat-opts').removeClass('hidden');
                } else {
                    $('.schedule-repeat-opts').addClass('hidden');
                }
            });

            function hideClasses(classes) {
                classes.forEach(function(className) {
                    $('.'+className).addClass('hidden');
                });
            }
            
            $('[name="repeat_interval"]').on('change', function() {
                let repeat_interval = $(this).val();

                if (repeat_interval == 'Daily') {
                    $('.daily-opts').removeClass('hidden');
                    hideClasses(['weekly-opts', 'monthly-opts']);
                } else if (repeat_interval == 'Weekly') {
                    hideClasses(['daily-opts', 'monthly-opts']);
                    $('.weekly-opts').removeClass('hidden');
                } else if (repeat_interval == 'Monthly') {
                    hideClasses(['daily-opts', 'weekly-opts']);
                    $('.monthly-opts').removeClass('hidden');
                } else {
                    hideClasses(['daily-opts', 'weekly-opts', 'monthly-opts']);
                }
            });

            $('[name="repeat_end_type"]').on('change', function() {
                let repeat_end_type = $(this).val();

                if (repeat_end_type == 'Never') {
                    $('.repeat-end-date').addClass('hidden');
                } else if (repeat_end_type == 'Date') {
                    $('.repeat-end-date').removeClass('hidden');
                }
            });

            $('.repeat-end-date').datepicker();
        });
    

        $('body').on('submit', '#task_form',  function(event){
            event.preventDefault();

            let form = document.getElementById('task_form');
            let formData = new FormData(form);

            url = "{{ route('tenant.crm.task.store', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location = "{{ route('tenant.crm.task.index', [$tenant->domain]) }}";
                }, 5000);  

                form.reset()
                
            }
            let failed = function(data){

            }

            ajaxCall(url, formData, success, failed);  

        });

        $('body').on('click', 'input[name="myself"]',  function(event){
            if($(this).prop('checked')){
                $("select[name='assignee']").addClass('hidden');
            }else{
                $("select[name='assignee']").removeClass('hidden');
            }
        });




    </script>


@endsection

@section('extra-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/wickedpicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-datetimepicker.min.css') }}">

    <style>
        .schedule-input {
            display: inline;
            width: auto;
        }

        .week-days input[type="checkbox"] {
            display: none;
        }
        .week-days input[type="checkbox"] + label {
            width: 100%;
            display: inline-block;
            text-align: center;
            padding: 0.9em 0;
            font-size: 0.9em;
            cursor: pointer;
            color: #666666;
            background-color: #EDF1F5;
        }
        .week-days input[type="checkbox"] + label:hover {
            background-color: #E0E6ED;
        }
        .week-days input[type="checkbox"]:checked + label {
            background-color: #787985;
            color: #eeeeee;
        }

        .week-days {
            display: flex;
        }
        .week-day {
            width: 50px;
        }
        .week-day label {
            border-right: 1px solid #787985;
        }
        .week-day:last-of-type label {
            border-right: none;
        }

        .bootstrap-datetimepicker-widget{
            z-index: 1200;
            position: absolute;
        }
        #ui-datepicker-div{
            z-index: 1200;
            background-color: #ffffff;
        }


    </style>

@endsection