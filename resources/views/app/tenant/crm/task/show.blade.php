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
                    <span class="h3"> {{ $task->title }} </span> 
                    <span class="pull-right m-r-10">
                <a class="btn btn-info show-sidebox-right-btn" href="javascript:;"><i class="fa fa-eye"></i> Show Task Info </a>
                        <a href="{{ route('tenant.crm.task.create', [$tenant->domain]) }}" class="btn btn-lg btn-outline-default"> <i class="fa fa-plus"></i> Add Task </a>
                        <a href="{{ route('tenant.crm.task.index', [$tenant->domain]) }}" class="btn btn-lg btn-outline-default"> <i class="fa fa-list"></i> List Task </a>

                        <div class="btn-group">
                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Action <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">

                                @if($task->status == 'open')
                                    <li><a href="javascript:;" class="change-status" data-status='completed' data-task_id="{{ $task->id }}">Completed</a></li>
                                @endif
                                <li role="separator" class="divider"></li>
                                <li><a href="javascript:;" data-task_id="{{ $task->id }}" data-toggle="modal" data-target=".delete-task-modal" >Delete Task</a></li>

                            </ul>
                        </div>
                    </span>
                </div> 
                <hr class="horizonal-line-thick">
            </div>

            <div class="panel-body" style="min-height: 400px;">

                <div class="col-md-12 col-sm-12 col-xs-12" id="tasks_container">

                    @include('app.tenant.crm.task.partials.details')

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
                        <input type="hidden" name="task_id" value="{{ $task->id }}">
                         
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

    <div class="modal fade" id="reply_comment_task_modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('tenant.crm.task.comment', [$tenant->domain])}}" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <input type="hidden" name="sub_set" value="">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"> Reply on a comment </h4>
                    </div>
                    <div class="modal-body clearfix">
                        @if ($task->status != 'Closed' || $task->status != 'Resolved')
        
                            <div class="col-md-12 form-group clearfix">
                                <div class="col-md-2">
                                    <label>Comment</label>
                                </div>
                                <div class="col-md-10">
                                    <textarea name="comment" class="summernote_editor form-control" rows="5"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12 form-group clearfix">
                                <div class="col-md-2">
                                    <label>Attachments</label>
                                </div>
                                <div class="col-md-10 clearfix">
                                    <button class="btn btn-success btn-transparent" onclick="event.preventDefault(); addresource('resource-container-modal');"> <i class="fa fa-plus"></i> Add Resources </button> (Size 3MB) (Max 5)
                                    
                                    <div id="resource-container-modal"></div>
                                </div>
                            </div>

                        @endif

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success"> <i class="fa fa-comment"></i> Send Comment </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <div class="row sidebox-container hide clearfix" style="clear: both; min-height: 100vh; left: 5px;">
        <div class="sidebox-content-area clearfix" style="padding-top: 0px;">

            <div class="sidebox-header"> <div class="h3 text-black" style="padding: 20px; margin: 0; position: relative; "> Task Details: {{ $task->title }}  <span class="pull-right"> <button type="button" class="btn btn-danger btn-transparent hide-sidebox-btn"> <i class="fa fa-times"></i> close </button> </span> </div> </div>
            <div class="sidebox-body clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-t2 clearfix">
                    
                    <div class="table-responsive clearfix">
                        <table class="table">
                            <tbody>

                                <tr>
                                    <th> Title </th>
                                    <td>  
                                        {{ $task->title }} 
                                        <a class="btn btn-link pull-right btn-xs" href="{{ route('tenant.crm.task.edit', [$tenant->domain, $task->id]) }}" ><i class="fa fa-edit"></i></a>
                                    </td>
                                    <th>Task Type</th>
                                    <td>{{ $task->type }}</td>
                                </tr>
                                <tr>
                                    <th> Assigner </th>
                                    <td>{{ $task->assigner->name }}</td>
                                    <th>Assignee</th>
                                    <td>{{ $task->assignee->name }}</td>
                                </tr>
                                <tr>
                                    <th>Start Date</th>
                                    <td>{{ $task->start_date }}</td>
                                    <th>Start Time</th>
                                    <td>{{ $task->start_time }} 
                                </tr>
                                <tr>
                                    <th>End Date</th>
                                    <td>{{ $task->end_date }} 
                                    <th>End Time</th>
                                    <td>{{ $task->end_time }} 
                                </tr>
                                <tr>
                                    <th>Priority</th>
                                    <td>{{ $task->priority }}</td>
                                    <th>Duration </th>
                                    <td>{{ $task->duration .' '. $task->duration_unit }} 
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>{{ $task->status }}</td>
                                    <th>Repeat Interval</th>
                                    <td>{{ $task->repeat() }}</td>
                                </tr>


                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive">
                        <p class="f-s-15 text-info"> Description: </p>
                        <div class="f-s-15"> {!! nl2br($task->description) !!} </div>
                    </div>

                </div>
            </div>

            <div class="sidebox-footer text-right" style="padding: 15px 30px;">
                <button type="button" class="btn btn-danger btn-transparent hide-sidebox-btn"> <i class="fa fa-times"></i> close </button>
            </div>
        </div>
        
    </div>


@endsection


@section('extra-script')

    <script>
       
        $mn_list = $('.sidebar ul.nav > li.nav-crm');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-task').addClass('active');


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
                    window.location = "{{ route('tenant.crm.task.index', [$tenant->domain]) }}";
                }, 3000);  

                form.reset();
                
            }
            let failed = function(data){

            }

            ajaxCall(url, formData, success, failed);  

        });


        $('#reply_comment_task_modal').on('show.bs.modal', function(evt) {
            var button = $(evt.relatedTarget);
            $('[name="sub_set"]').val(button.data('sub_set'));
        });
    
    </script>


@endsection

@section('extra-css')
        
    <style>


    </style>

@endsection