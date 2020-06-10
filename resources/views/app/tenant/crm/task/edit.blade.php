@extends('layouts.tenant_sidebar')

@section('title')
    
    TASK MANAGEMENT

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.crm.index', $tenant->domain) }}"> CRM </a></li>
    <li><a href="{{ route('tenant.crm.task.index', $tenant->domain) }}"> TASK </a></li>
    <li class="active">Edit Task </li>

@endsection

@section('content')

   
    <div class="col-md-12 col-sm-12 col-xs-12 p-0">
        <div class="panel panel-default">
            <div class="panel-heading"> 
            
                <div class="panel-title"> 
                    <span class="h3"> Edit Task &nbsp; <span class="text-primary">  </span> </span> 
                    <span class="pull-right m-r-10">
                        <a href="{{ route('tenant.crm.task.index', [$tenant->domain]) }}" class="btn btn-lg btn-outline-default"> <i class="fa fa-list"></i> List Task </a>
                    </span>
                </div> 
                <hr class="horizonal-line-thick" />
            </div>

            <div class="panel-body" style="min-height: 400px;">

                <div class="col-md-12 col-sm-12 col-xs-12 bg-silver" >

                    @include('app.tenant.crm.task.partials.edit_form')

                </div>

            </div>
        </div>
    </div>

        



@endsection


@section('extra-script')

    <script type="text/javascript" src="{{ asset('js/wickedpicker.js') }}"></script>
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
        });
    

        $('body').on('submit', '#edit_task_form',  function(event){
            event.preventDefault();

            let form = document.getElementById('edit_task_form');
            let formData = new FormData(form);

            url = "{{ route('tenant.crm.task.update', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location = "{{ route('tenant.crm.task.show', [$tenant->domain, $task->id]) }}";
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
    <style>


    </style>

@endsection