<div class="table-responsive">

    <table class="table table-hovered table-striped">
    
        <thead>
            <tr>
                <th>S/N</th>
                <th>Title</th>
                <th>Assigner</th>
                <th>Assignee</th>
                <th>Type</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Start</th>
                <th>End</th>
                <th>Repeat Interval</th>
                <th>Action</th>

            </tr>
        </thead>

        <tbody>
            @foreach($tasks as $index => $task)
                <tr>
                    <td>{{$index+1}}</td>
                    <td> <a href="{{ route('tenant.crm.task.show', [$tenant->domain, $task->id]) }}"> {{$task->title}} </a> </td>
                    <td> {{ $task->assigner->name }} </td>
                    <td> {{ $task->assignee->name }} </td>
                    <td> {{ $task->type }} </td>
                    <td> {{ $task->priority }} </td>
                    <td> {{ $task->status }} </td>
                    <td> {{ $task->start_date .' '. $task->start_time }} </td>
                    <td> {{ $task->end_date .' '. $task->end_time }} </td>
                    <td> {!! $task->repeat() !!} </td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                    </td>
                </tr>
            @endforeach
        </tbody>
    
    </table>

    {{ $tasks->links() }}

</div>