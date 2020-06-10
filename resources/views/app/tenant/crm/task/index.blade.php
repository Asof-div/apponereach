@extends('layouts.tenant_sidebar')

@section('title')
    
    TASK MANAGEMENT

@endsection

@section('breadcrumb')

    <li><a href="{{ url($tenant->domain.'/dashboard') }}"> Dashboard </a></li>
    <li><a href="{{ url($tenant->domain.'/crm') }}"> CRM </a></li>
    <li class="active"> Task </li>

@endsection

@section('content')

    <div class="col-md-12 col-sm-12 col-xs-12 p-0">
        <div class="panel panel-default">
            <div class="panel-heading"> 
            
                <div class="panel-title"> 
                    <span class="h3"> Total Tasks &nbsp; <span class="text-primary"> {{ $tasks->count() }} </span> </span> 
                    <span class="pull-right m-r-10">
                        <a href="{{ route('tenant.crm.task.create', [$tenant->domain]) }}" class="btn btn-lg btn-outline-default"> <i class="fa fa-plus-circle"></i> Add Task </a>
                    </span>

                    <div class="panel-heading-btn m-r-10 m-t-10">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand" data-original-title="" title=""><i class="fa fa-expand"></i></a>
                    </div>
                </div> 
                <hr class="horizonal-line-thick" />

            </div>

            <div class="panel-body" style="min-height: 400px;">

                <div class="col-md-12 col-sm-12 col-xs-12" id="tasks_container">

                    @include('app.tenant.crm.task.partials.table')

                </div>

            </div>
        </div>
    </div>

    <div class="modal fade delete-task-modal" tabindex="1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <form method="post" action="{{ route('tenant.crm.task.delete', [$tenant->domain]) }}" id="delete_task_form">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span> </button>
                        <h5 class="modal-title"> <span class="h4 text-primary"> DELETE TASK </span> </h5>
                    </div>

                    <div class="modal-body">
                     
                        {{ csrf_field() }}
                        <p class="f-s-15"> Are you sure you want to delete this ? </p>
                        <input type="hidden" name="task_id" value="">
                         
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
       
        $mn_list = $('.sidebar ul.nav > li.nav-crm');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-task').addClass('active');

        $('.delete-task-modal').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget);
            let task_id = button.data('task_id');

            var modal = $(this)
            modal.find('input[name=task_id]').val(task_id);
        });

        $('body').on('click', '.change-status',  function(event){
            event.preventDefault();
            let _token = "{{ csrf_token() }}"
            let task_id = $(this).attr('data-task_id');
            let status = $(this).attr('data-status');
            let formData = new FormData();
            formData.append('_token', _token);
            formData.append('task_id', task_id);
            formData.append('status', status);

            url = "{{ route('tenant.crm.task.status', [$tenant->domain]) }}"; 

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

       $('body').on('submit', '#delete_task_form',  function(event){
            $('.modal').hide();
            event.preventDefault();
            let form = document.getElementById('delete_task_form');
            let formData = new FormData(form);

            url = "{{ route('tenant.crm.task.delete', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location = data.url;
                }, 3000);  

                form.reset();
                
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