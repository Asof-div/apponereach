<form id="edit_task_form" class="form-horizontal" name="13" action="" enctype='multipart/form-data' method='post'>

	 <div class="form-group bg-white clearfix p-15 m-r-0 m-l-0 m-t-15">
        <div class="pull-right" >
            <button class="btn btn-default" type="reset"> <i class="fa fa-exclamation-circle"></i> Cancel</button> &nbsp;
            <button class="btn btn-primary" type="submit"> <i class="fa fa-save"></i> Update Task </button>
        </div>
    </div>     
    
    <div class="p-20 col-md-12 bg-white m-b-15">

        {{csrf_field()}}
        <input type="hidden" name="task_id" value="{{ $task->id }}">
        <div class="col-md-6 col-sm-6">
            <div class="form-group">
                <label class="form-label col-md-4">Start Date <i class="fa fa-asterisk text-danger"></i> </label>
                <div class="col-md-8">
                    <input type="text" name="start_date" value="{{ $task->start_date }}" class="form-control datepicker">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-md-4">Start Time <i class="fa fa-asterisk text-danger"></i> </label>
                <div class="col-md-8">
                    <input type="text" name="start_time" value="{{ $task->start_time }}" class="form-control timepicker">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-md-4">Duration <i class="fa fa-asterisk text-danger"></i></label>
                <div class="col-md-8">
                    <div class="my-group">
                        <input type="text" name="duration" value="{{ $task->duration }}" class="form-control">
                        <select class="form-control" name="duration_unit">
                            <option {{ $task->duration_unit == 'Hour' ? 'selected' : '' }} value="Hour">Hour</option>
                            <option {{ $task->duration_unit == 'Week' ? 'selected' : '' }} value="Week">Week</option>
                            <option {{ $task->duration_unit == 'Day' ? 'selected' : '' }} value="Day">Day</option>
                            <option {{ $task->duration_unit == 'Month' ? 'selected' : '' }} value="Month">Month</option>
                        </select>
                    </div>
                </div>
            </div>
            

            <div class="form-group">
                <label class="form-label col-md-4">Priority <i class="fa fa-asterisk text-danger"></i></label>
                <div class="col-md-8">
                    <select name="priority" class="form-control">
                    	@foreach($taskHelper->getPriority() as $priority)

                    		<option {{ $task->priority == $priority ? 'selected' : '' }} value="{{ $priority }}"> {{ $priority }} </option>

                    	@endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-md-4">Task Type <i class="fa fa-asterisk text-danger"></i></label>
                <div class="col-md-8">
                    <select name="task_type" class="form-control">
                    	@foreach($taskHelper->getType() as $type)

                    		<option {{ $task->type == $type ? 'selected' : '' }} value="{{ $type }}"> {{ $type }} </option>

                    	@endforeach                       
                    </select>
                </div>
            </div>

            
        </div>

        <div class="col-md-6 col-sm-6">

            <div class="form-group">
                <label class="form-label col-md-4">Assignee <i class="fa fa-asterisk text-danger"></i></label>
                <div class="col-md-8">
                    <label style="font-size: 15px; vertical-align: middle; text-align: center;"><span style="margin-top: 0px; display: inline-block;"> Self &nbsp;</span>  <input type="checkbox" name="myself" class="form-control" {{ $task->assignee_id == Auth::id() ? 'checked' : '' }} value="{{ Auth::id() }}" style="display: inline-block; width: 20px; height: 20px; margin-top: 10px;"> </label>
                    <select name="assignee" class="form-control assignee">
                        @foreach($users as $user)
                            <option {{ $task->assignee_id == $user->id ? 'selected' : '' }} value="{{ $user->id }}"> {{ $user->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-md-4">Title <i class="fa fa-asterisk text-danger"></i></label>
                <div class="col-md-8">
                    <input type="text" name="title" class="form-control" value="{{ $task->title }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-md-4">Description</label>
                <div class="col-md-8">
                    <textarea class="form-control" rows="7" name="description">{{ $task->description }}</textarea>
                </div>
            </div>
            
        </div>


    </div>

	
</form>