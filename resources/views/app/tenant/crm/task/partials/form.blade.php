<form id="task_form" class="form-horizontal" name="13" action="" enctype='multipart/form-data' method='post'>

	 <div class="form-group bg-white clearfix p-15 m-r-0 m-l-0 m-t-15">
        <div class="pull-right" >
            <button class="btn btn-default" type="reset"> <i class="fa fa-exclamation-circle"></i> Cancel</button> &nbsp;
            <button class="btn btn-primary" type="submit"> <i class="fa fa-save"></i> Save Task </button>
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

            
            <div class="form-group">
                <label class="form-label col-md-4">Assignee <i class="fa fa-asterisk text-danger"></i></label>
                <div class="col-md-8">
                    <label style="font-size: 15px; vertical-align: middle; text-align: center;"><span style="margin-top: 0px; display: inline-block;"> Self &nbsp;</span>  <input type="checkbox" name="myself" class="form-control" {{ $task->assignee_id == Auth::id() ? 'checked' : '' }} value="{{ Auth::id() }}" style="display: inline-block; width: 20px; height: 20px; margin-top: 10px;"> </label>
                    <select name="assignee" class="form-control assignee hidden">
                        @foreach($users as $user)
                            <option {{ $task->assignee_id == $user->id ? 'selected' : '' }} value="{{ $user->id }}"> {{ $user->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
        </div>

        <div class="col-md-6 col-sm-6">

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

                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <div class="checkbox-inline">
                            <label><input type="checkbox" name="repeat_task"> Repeat Task</label>
                        </div>
                    </div>

                    <div class="schedule-repeat-opts hidden">
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <select name="repeat_interval" class="form-control">
                                <option value="">--</option>
                                <option value="Daily">Daily</option>
                                <option value="Weekly">Weekly</option>
                                <option value="Monthly">Monthly</option>
                            </select>
                        </div>

                        <div class="daily-opts hidden">
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <label>Repeat Every:</label>
                                <input type="number" name="daily_repeat_freq" value="1" min="1" max="7" class="form-control schedule-input"> day(s)
                            </div>
                        </div>

                        <div class="weekly-opts hidden">
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <label>Repeat Every:</label>
                                <input type="number" name="weekly_repeat_freq" value="1" min="1" max="3" class="form-control schedule-input"> week(s)
                            </div>

                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <div class="week-days">
                                    <div class="week-day">
                                        <input type="checkbox" name="weekly_repeat_days[]" id="SU" value="SU" >
                                        <label for="SU">Sun</label>
                                    </div>
                                    <div class="week-day">
                                        <input type="checkbox" name="weekly_repeat_days[]" id="MO" value="MO" >
                                        <label for="MO">Mon</label>
                                    </div>
                                    <div class="week-day">
                                        <input type="checkbox" name="weekly_repeat_days[]" id="TU" value="TU" >
                                        <label for="TU">Tue</label>
                                    </div>
                                    <div class="week-day">
                                        <input type="checkbox" name="weekly_repeat_days[]" id="WE" value="WE" >
                                        <label for="WE">Wed</label>
                                    </div>
                                    <div class="week-day">
                                        <input type="checkbox" name="weekly_repeat_days[]" id="TH" value="TH" >
                                        <label for="TH">Thu</label>
                                    </div>
                                    <div class="week-day">
                                        <input type="checkbox" name="weekly_repeat_days[]" id="FR" value="FR" >
                                        <label for="FR">Fri</label>
                                    </div>
                                    <div class="week-day">
                                        <input type="checkbox" name="weekly_repeat_days[]" id="SA" value="SA" >
                                        <label for="SA">Sat</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="monthly-opts hidden">
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <label>Repeat Every:</label>
                                <input type="number" name="monthly_repeat_freq" value="1" min="1" max="12" class="form-control schedule-input"> month(s)
                            </div>

                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <div class="radio-inline">
                                    <label><input type="radio" name="monthly_repeat_type" value="day_num" checked> on day</label>
                                </div>
                                <select name="monthly_day_num" class="form-control schedule-input">
                                    @foreach(range(1,31) as $day)
                                    <option value="{{ $day }}">{{ $day }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <div class="radio-inline">
                                    <label><input type="radio" name="monthly_repeat_type" value="day_pos"> on the</label>
                                </div>
                                <select name="monthly_day_pos" class="form-control schedule-input">
                                    <option value="1">First</option>
                                    <option value="2">Second</option>
                                    <option value="3">Third</option>
                                    <option value="4">Fourth</option>
                                    <option value="-1">Last</option>
                                </select>
                                <select name="monthly_day_name" class="form-control schedule-input">
                                    <option value="SU">Sunday</option>
                                    <option value="MO">Monday</option>
                                    <option value="TU">Tuesday</option>
                                    <option value="WE">Wednesday</option>
                                    <option value="TH">Thursday</option>
                                    <option value="FR">Friday</option>
                                    <option value="SA">Saturday</option>
                                </select>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group col-md-12 col-sm-12 col-xs-12 repeat-end-opts">
                            <label>Repeat End:</label>
                            <select name="repeat_end_type" class="form-control schedule-input">
                                <option value="Never">Never</option>
                                <option value="Date">On date</option>
                            </select>

                            <input type="text" name="repeat_end_date" class="form-control repeat-end-date hidden schedule-input">
                        </div>
                    </div>

            
        </div>


    </div>

	
</form>