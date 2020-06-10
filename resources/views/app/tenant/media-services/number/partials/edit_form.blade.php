<div class="panel panel-with-tabs clearfix p-t-20 p-r-20 p-l-20" style="overflow-x: auto;">
    <h4 class="text-primary">EDIT TIME SCHEDULE </h4>

    <form id="call_flow_form" class="form-horizontal" name="13" action="{{ route('tenant.media-service.timer.update', [$tenant->domain]) }}" enctype='multipart/form-data' method='post'>

        {{csrf_field()}}

        <input type="hidden" name="timer_id" value="{{ $timer->id }}">
        <input type="hidden" name="start_time" id="start_time" value="{{ $timer->start_time }}">
        <input type="hidden" name="end_time" id="end_time" value="{{ $timer->end_time }}">
        <input type="hidden" name="start_day" id="start_day" value="{{ $timer->start_day }}">
        <input type="hidden" name="end_day" id="end_day" value="{{ $timer->end_day }}">
        <input type="hidden" name="start_mon" id="start_mon" value="{{ $timer->start_mon }}">
        <input type="hidden" name="custom_day" id="custom_day" value="{{ $timer->custom_day }}">
        <input type="hidden" name="weekdays" id="weekdays" value="{{ $timer->days }}">


        <div class="row" style="padding: 15px 25px;">

            <div class="form-group " style="border-bottom: 1px dashed #ccc; ">
                <label class="control-label col-md-3 f-s-15 f-w-300 m-t-20"> Strategy </label>
                <div class="col-md-9">    
                    <label class="radio m-15">
                        <input type="radio" name="period" class="selected-period" {{ $timer->period == 'all' ? 'checked' : '' }} value="all">
                        <span class="f-s-15">24 hours Open Office</span>
                        <p>Choose this option if you want incoming calls to be handles the same way all the time.</p>
                    </label>
                    <label class="radio m-15">
                        <input type="radio" name="period" class="selected-period" {{ $timer->period == 'custom' ? 'checked' : '' }} value="custom">
                        <span class="f-s-15">Custom Work Office Hours </span>
                        <p>Chose this option if you want incoming calls to be handles differently when your office is closed.</p>
                    </label>
                    <label class="radio m-15">
                        <input type="radio" name="period" class="selected-period" {{ $timer->period == 'date' ? 'checked' : '' }} value="date">
                        <span class="f-s-15">Specific Date or Holiday </span>
                        <p>Choose this option if you want incoming calls to be handles differently on specific date, for example every 25th of December. </p>
                    </label>
                    <label class="radio m-15">
                        <input type="radio" name="period" class="selected-period" {{ $timer->period == 'custom_date' ? 'checked' : '' }} value="custom_date">
                        <span class="f-s-15">Custom Recurrent Date </span>
                        <p>Choose this option if you want incoming calls to be handles differently, every first monday of a particular month.</p>
                    </label>
                    <label class="radio m-15">
                        <input type="radio" name="period" class="selected-period" {{ $timer->period == 'range' ? 'checked' : '' }} value="range">
                        <span class="f-s-15">Date Range </span>
                        <p>Choose this option if you want incoming calls to be handles differently between a specific date period. eg. 1nd - 5th January</p>
                    </label>
                </div>
            </div>

            <div class="form-group clearfix">
                <label class="control-label col-md-3 f-s-15 f-w-300"> Title </label>
                <div class="col-md-9">
                    <input type="text" name="title" class="form-control" required="required" value="{{ old('title') ? old('title') : $timer->title }}">
                </div>
            </div>


            <div class="form-group hour-strategy strategy clearfix">

                <label class="control-label col-md-3 f-s-15 f-w-300"> <i class="fa fa-soundcloud "></i> Day Of Week </label>
                <div class="col-md-9">

                    <label class="day_of_week checkbox-inline" >
                        <input type="checkbox" checked="checked" disabled="disabled" class="day-of-week" name="day_of_week[]" value="1">
                        SUN
                    </label>

                    <label class="day_of_week checkbox-inline" >
                        <input type="checkbox" checked="checked" disabled="disabled" class="day-of-week" name="day_of_week[]" value="2">
                        MON
                    </label>
                    
                    <label class="day_of_week checkbox-inline" >
                        <input type="checkbox" checked="checked" disabled="disabled" class="day-of-week" name="day_of_week[]" value="4">
                        TUE
                    </label>

                    <label class="day_of_week checkbox-inline" >
                        <input type="checkbox" checked="checked" disabled="disabled" class="day-of-week" name="day_of_week[]" value="8">
                        WED
                    </label>

                    <label class="day_of_week checkbox-inline" >
                        <input type="checkbox" checked="checked" disabled="disabled" class="day-of-week" name="day_of_week[]" value="16">
                        THU
                    </label>

                    <label class="day_of_week checkbox-inline" >
                        <input type="checkbox" checked="checked" disabled="disabled" class="day-of-week" name="day_of_week[]" value="32">
                        FRI
                    </label>

                    <label class="day_of_week checkbox-inline" >
                        <input type="checkbox" checked="checked" disabled="disabled" class="day-of-week" name="day_of_week[]" value="64">
                        SAT 
                    </label>
                
                </div>

            </div>

            <div class="form-group clearfix hour-strategy strategy timepicker clearfix">
                <label class="control-label col-md-3 f-s-15 f-w-300"> <i class="fa fa-clock-o "></i> Time Of Day </label>
                <div class="col-md-9">
                    <span class="checkbox-inline timebox">
                        <input type="text" class="form-control time start" value="{{ $timer->start_time }}">
                    </span>
                    <span class="checkbox-inline"> to </span>
                    <span class="checkbox-inline timebox">
                        <input type="text" class="form-control time end" value="{{ $timer->end_time }}">
                    </span>
                </div>
            </div>


            <div class="form-group date-strategy strategy timepicker {{ $timer->period != 'date' ? 'hide' : '' }} clearfix">
                
                <label class="control-label col-md-3 f-s-15 f-w-300"> <i class="fa fa-clock-o "></i> Specific Date & Time </label>
                <div class="col-md-9">
                    <span class="checkbox-inline">
                        <select class="form-control start_mon">
                            <option {{ $timer->start_mon == 'JAN' ? 'selected' : '' }} value="JAN">JAN</option>
                            <option {{ $timer->start_mon == 'FEB' ? 'selected' : '' }} value="FEB">FEB</option>
                            <option {{ $timer->start_mon == 'MAR' ? 'selected' : '' }} value="MAR">MAR</option>
                            <option {{ $timer->start_mon == 'APR' ? 'selected' : '' }} value="APR">APR</option>
                            <option {{ $timer->start_mon == 'MAY' ? 'selected' : '' }} value="MAY">MAY</option>
                            <option {{ $timer->start_mon == 'JUN' ? 'selected' : '' }} value="JUN">JUN</option>
                            <option {{ $timer->start_mon == 'JUL' ? 'selected' : '' }} value="JUL">JUL</option>
                            <option {{ $timer->start_mon == 'AUG' ? 'selected' : '' }} value="AUG">AUG</option>
                            <option {{ $timer->start_mon == 'SEP' ? 'selected' : '' }} value="SEP">SEP</option>
                            <option {{ $timer->start_mon == 'OCT' ? 'selected' : '' }} value="OCT">OCT</option>
                            <option {{ $timer->start_mon == 'NOV' ? 'selected' : '' }} value="NOV">NOV</option>
                            <option {{ $timer->start_mon == 'DEC' ? 'selected' : '' }} value="DEC">DEC</option>
                            <option {{ $timer->start_mon == 'ANY' ? 'selected' : '' }} value="ANY">ANY</option>
                        </select>
                    </span>
                    <span class="checkbox-inline">
                        <select class="form-control start_day">
                            <option {{ $timer->start_day == 1 ? 'selected' : '' }} value="1">1</option>
                            <option {{ $timer->start_day == 2 ? 'selected' : '' }} value="2">2</option>
                            <option {{ $timer->start_day == 3 ? 'selected' : '' }} value="3">3</option>
                            <option {{ $timer->start_day == 4 ? 'selected' : '' }} value="4">4</option>
                            <option {{ $timer->start_day == 5 ? 'selected' : '' }} value="5">5</option>
                            <option {{ $timer->start_day == 6 ? 'selected' : '' }} value="6">6</option>
                            <option {{ $timer->start_day == 7 ? 'selected' : '' }} value="7">7</option>
                            <option {{ $timer->start_day == 8 ? 'selected' : '' }} value="8">8</option>
                            <option {{ $timer->start_day == 9 ? 'selected' : '' }} value="9">9</option>
                            <option {{ $timer->start_day == 10 ? 'selected' : '' }} value="10">10</option>
                            <option {{ $timer->start_day == 11 ? 'selected' : '' }} value="11">11</option>
                            <option {{ $timer->start_day == 12 ? 'selected' : '' }} value="12">12</option>
                            <option {{ $timer->start_day == 13 ? 'selected' : '' }} value="13">13</option>
                            <option {{ $timer->start_day == 14 ? 'selected' : '' }} value="14">14</option>
                            <option {{ $timer->start_day == 15 ? 'selected' : '' }} value="15">15</option>
                            <option {{ $timer->start_day == 16 ? 'selected' : '' }} value="16">16</option>
                            <option {{ $timer->start_day == 17 ? 'selected' : '' }} value="17">17</option>
                            <option {{ $timer->start_day == 18 ? 'selected' : '' }} value="18">18</option>
                            <option {{ $timer->start_day == 19 ? 'selected' : '' }} value="19">19</option>
                            <option {{ $timer->start_day == 20 ? 'selected' : '' }} value="20">20</option>
                            <option {{ $timer->start_day == 21 ? 'selected' : '' }} value="21">21</option>
                            <option {{ $timer->start_day == 22 ? 'selected' : '' }} value="22">22</option>
                            <option {{ $timer->start_day == 23 ? 'selected' : '' }} value="23">23</option>
                            <option {{ $timer->start_day == 24 ? 'selected' : '' }} value="24">24</option>
                            <option {{ $timer->start_day == 25 ? 'selected' : '' }} value="25">25</option>
                            <option {{ $timer->start_day == 26 ? 'selected' : '' }} value="26">26</option>
                            <option {{ $timer->start_day == 27 ? 'selected' : '' }} value="27">27</option>
                            <option {{ $timer->start_day == 28 ? 'selected' : '' }} value="28">28</option>
                            <option {{ $timer->start_day == 29 ? 'selected' : '' }} value="29">29</option>
                            <option {{ $timer->start_day == 30 ? 'selected' : '' }} value="30">30</option>
                            <option {{ $timer->start_day == 31 ? 'selected' : '' }} value="31">31</option>
                        </select>
                    </span>

                    <span class="checkbox-inline timebox">
                        <input type="text" class="form-control time start">
                    </span>
                    <span class="checkbox-inline"> to </span>
                    <span class="checkbox-inline timebox">
                        <input type="text" class="form-control time end">
                    </span>
                </div>
            </div>


            <div class="form-group custom-date-strategy strategy timepicker {{ $timer->period != 'custom_date' ? 'hide' : '' }} clearfix">
                
                <label class="control-label col-md-3 f-s-15 f-w-300"> <i class="fa fa-clock-o "></i> Custom Date </label>
                <div class="col-md-9">
                    <span class="checkbox-inline">
                        <select class="form-control start_mon">
                            <option {{ $timer->start_mon == 'JAN' ? 'selected' : '' }} value="JAN">JAN</option>
                            <option {{ $timer->start_mon == 'FEB' ? 'selected' : '' }} value="FEB">FEB</option>
                            <option {{ $timer->start_mon == 'MAR' ? 'selected' : '' }} value="MAR">MAR</option>
                            <option {{ $timer->start_mon == 'APR' ? 'selected' : '' }} value="APR">APR</option>
                            <option {{ $timer->start_mon == 'MAY' ? 'selected' : '' }} value="MAY">MAY</option>
                            <option {{ $timer->start_mon == 'JUN' ? 'selected' : '' }} value="JUN">JUN</option>
                            <option {{ $timer->start_mon == 'JUL' ? 'selected' : '' }} value="JUL">JUL</option>
                            <option {{ $timer->start_mon == 'AUG' ? 'selected' : '' }} value="AUG">AUG</option>
                            <option {{ $timer->start_mon == 'SEP' ? 'selected' : '' }} value="SEP">SEP</option>
                            <option {{ $timer->start_mon == 'OCT' ? 'selected' : '' }} value="OCT">OCT</option>
                            <option {{ $timer->start_mon == 'NOV' ? 'selected' : '' }} value="NOV">NOV</option>
                            <option {{ $timer->start_mon == 'DEC' ? 'selected' : '' }} value="DEC">DEC</option>
                            <option {{ $timer->start_mon == 'ANY' ? 'selected' : '' }} value="ANY">ANY</option>
                        </select>
                    </span>
                    <span class="checkbox-inline">
                        <select class="form-control custom_day">
                            <option {{ $timer->custom_day == 'First' ? 'selected' : '' }} value="First">First</option>
                            <option {{ $timer->custom_day == 'Second' ? 'selected' : '' }} value="Second">Second</option>
                            <option {{ $timer->custom_day == 'Third' ? 'selected' : '' }} value="Third">Third</option>
                            <option {{ $timer->custom_day == 'Fourth' ? 'selected' : '' }} value="Fourth">Fourth</option>
                            <option {{ $timer->custom_day == 'Fifth' ? 'selected' : '' }} value="Fifth">Firth</option>
                            <option {{ $timer->custom_day == 'Last' ? 'selected' : '' }} value="Last">Last</option>
                            
                        </select>
                    </span>
                    <span class="checkbox-inline">
                        <select class="form-control weekday">
                            <option {{ $timer->days == 2 ? 'selected' : '' }} value="2">MON</option>
                            <option {{ $timer->days == 4 ? 'selected' : '' }} value="4">TUE</option>
                            <option {{ $timer->days == 8 ? 'selected' : '' }} value="8">WED</option>
                            <option {{ $timer->days == 16 ? 'selected' : '' }} value="16">THU</option>
                            <option {{ $timer->days == 32 ? 'selected' : '' }} value="32">FRI</option>
                            <option {{ $timer->days == 64 ? 'selected' : '' }} value="64">SAT</option>
                            <option {{ $timer->days == 1 ? 'selected' : '' }} value="1">SUN</option>
                        </select>
                    </span>
                    <span class="checkbox-inline timebox">
                        <input type="text" class="form-control time start" value="{{ $timer->start_time }}">
                    </span>
                    <span class="checkbox-inline"> to </span>
                    <span class="checkbox-inline timebox">
                        <input type="text" class="form-control time end" value="{{ $timer->end_time }}">
                    </span>
                </div>
            </div>


            <div class="form-group date-range-strategy strategy timepicker {{ $timer->period != 'range' ? 'hide' : '' }} clearfix">
                <label class="control-label col-md-3 f-s-15 f-w-300"> <i class="fa fa-clock-o "></i> Date Range </label>
                <div class="col-md-9">
                    <span class="checkbox-inline">
                        <select class="form-control start_mon">
                            <option {{ $timer->start_mon == 'JAN' ? 'selected' : '' }} value="JAN">JAN</option>
                            <option {{ $timer->start_mon == 'FEB' ? 'selected' : '' }} value="FEB">FEB</option>
                            <option {{ $timer->start_mon == 'MAR' ? 'selected' : '' }} value="MAR">MAR</option>
                            <option {{ $timer->start_mon == 'APR' ? 'selected' : '' }} value="APR">APR</option>
                            <option {{ $timer->start_mon == 'MAY' ? 'selected' : '' }} value="MAY">MAY</option>
                            <option {{ $timer->start_mon == 'JUN' ? 'selected' : '' }} value="JUN">JUN</option>
                            <option {{ $timer->start_mon == 'JUL' ? 'selected' : '' }} value="JUL">JUL</option>
                            <option {{ $timer->start_mon == 'AUG' ? 'selected' : '' }} value="AUG">AUG</option>
                            <option {{ $timer->start_mon == 'SEP' ? 'selected' : '' }} value="SEP">SEP</option>
                            <option {{ $timer->start_mon == 'OCT' ? 'selected' : '' }} value="OCT">OCT</option>
                            <option {{ $timer->start_mon == 'NOV' ? 'selected' : '' }} value="NOV">NOV</option>
                            <option {{ $timer->start_mon == 'DEC' ? 'selected' : '' }} value="DEC">DEC</option>
                            <option {{ $timer->start_mon == 'ANY' ? 'selected' : '' }} value="ANY">ANY</option>
                        </select>
                    </span>
                    <span class="checkbox-inline">
                        <select class="form-control start_day">
                            <option {{ $timer->start_day == 1 ? 'selected' : '' }} value="1">1</option>
                            <option {{ $timer->start_day == 2 ? 'selected' : '' }} value="2">2</option>
                            <option {{ $timer->start_day == 3 ? 'selected' : '' }} value="3">3</option>
                            <option {{ $timer->start_day == 4 ? 'selected' : '' }} value="4">4</option>
                            <option {{ $timer->start_day == 5 ? 'selected' : '' }} value="5">5</option>
                            <option {{ $timer->start_day == 6 ? 'selected' : '' }} value="6">6</option>
                            <option {{ $timer->start_day == 7 ? 'selected' : '' }} value="7">7</option>
                            <option {{ $timer->start_day == 8 ? 'selected' : '' }} value="8">8</option>
                            <option {{ $timer->start_day == 9 ? 'selected' : '' }} value="9">9</option>
                            <option {{ $timer->start_day == 10 ? 'selected' : '' }} value="10">10</option>
                            <option {{ $timer->start_day == 11 ? 'selected' : '' }} value="11">11</option>
                            <option {{ $timer->start_day == 12 ? 'selected' : '' }} value="12">12</option>
                            <option {{ $timer->start_day == 13 ? 'selected' : '' }} value="13">13</option>
                            <option {{ $timer->start_day == 14 ? 'selected' : '' }} value="14">14</option>
                            <option {{ $timer->start_day == 15 ? 'selected' : '' }} value="15">15</option>
                            <option {{ $timer->start_day == 16 ? 'selected' : '' }} value="16">16</option>
                            <option {{ $timer->start_day == 17 ? 'selected' : '' }} value="17">17</option>
                            <option {{ $timer->start_day == 18 ? 'selected' : '' }} value="18">18</option>
                            <option {{ $timer->start_day == 19 ? 'selected' : '' }} value="19">19</option>
                            <option {{ $timer->start_day == 20 ? 'selected' : '' }} value="20">20</option>
                            <option {{ $timer->start_day == 21 ? 'selected' : '' }} value="21">21</option>
                            <option {{ $timer->start_day == 22 ? 'selected' : '' }} value="22">22</option>
                            <option {{ $timer->start_day == 23 ? 'selected' : '' }} value="23">23</option>
                            <option {{ $timer->start_day == 24 ? 'selected' : '' }} value="24">24</option>
                            <option {{ $timer->start_day == 25 ? 'selected' : '' }} value="25">25</option>
                            <option {{ $timer->start_day == 26 ? 'selected' : '' }} value="26">26</option>
                            <option {{ $timer->start_day == 27 ? 'selected' : '' }} value="27">27</option>
                            <option {{ $timer->start_day == 28 ? 'selected' : '' }} value="28">28</option>
                            <option {{ $timer->start_day == 29 ? 'selected' : '' }} value="29">29</option>
                            <option {{ $timer->start_day == 30 ? 'selected' : '' }} value="30">30</option>
                            <option {{ $timer->start_day == 31 ? 'selected' : '' }} value="31">31</option>
                        </select>
                    </span>
                    <span class="checkbox-inline timebox">
                        <input type="text" class="form-control time start" value="{{ $timer->start_time }}">
                    </span>
                    <span class="checkbox-inline"> to </span>

                    <span class="checkbox-inline timebox">
                        <input type="text" class="form-control time end" value="{{ $timer->end_time }}">
                    </span>
                    <span class="checkbox-inline">
                        <select class="form-control end_day">
                            <option {{ $timer->end_day == 1 ? 'selected' : '' }} value="1">1</option>
                            <option {{ $timer->end_day == 2 ? 'selected' : '' }} value="2">2</option>
                            <option {{ $timer->end_day == 3 ? 'selected' : '' }} value="3">3</option>
                            <option {{ $timer->end_day == 4 ? 'selected' : '' }} value="4">4</option>
                            <option {{ $timer->end_day == 5 ? 'selected' : '' }} value="5">5</option>
                            <option {{ $timer->end_day == 6 ? 'selected' : '' }} value="6">6</option>
                            <option {{ $timer->end_day == 7 ? 'selected' : '' }} value="7">7</option>
                            <option {{ $timer->end_day == 8 ? 'selected' : '' }} value="8">8</option>
                            <option {{ $timer->end_day == 9 ? 'selected' : '' }} value="9">9</option>
                            <option {{ $timer->end_day == 10 ? 'selected' : '' }} value="10">10</option>
                            <option {{ $timer->end_day == 11 ? 'selected' : '' }} value="11">11</option>
                            <option {{ $timer->end_day == 12 ? 'selected' : '' }} value="12">12</option>
                            <option {{ $timer->end_day == 13 ? 'selected' : '' }} value="13">13</option>
                            <option {{ $timer->end_day == 14 ? 'selected' : '' }} value="14">14</option>
                            <option {{ $timer->end_day == 15 ? 'selected' : '' }} value="15">15</option>
                            <option {{ $timer->end_day == 16 ? 'selected' : '' }} value="16">16</option>
                            <option {{ $timer->end_day == 17 ? 'selected' : '' }} value="17">17</option>
                            <option {{ $timer->end_day == 18 ? 'selected' : '' }} value="18">18</option>
                            <option {{ $timer->end_day == 19 ? 'selected' : '' }} value="19">19</option>
                            <option {{ $timer->end_day == 20 ? 'selected' : '' }} value="20">20</option>
                            <option {{ $timer->end_day == 21 ? 'selected' : '' }} value="21">21</option>
                            <option {{ $timer->end_day == 22 ? 'selected' : '' }} value="22">22</option>
                            <option {{ $timer->end_day == 23 ? 'selected' : '' }} value="23">23</option>
                            <option {{ $timer->end_day == 24 ? 'selected' : '' }} value="24">24</option>
                            <option {{ $timer->end_day == 25 ? 'selected' : '' }} value="25">25</option>
                            <option {{ $timer->end_day == 26 ? 'selected' : '' }} value="26">26</option>
                            <option {{ $timer->end_day == 27 ? 'selected' : '' }} value="27">27</option>
                            <option {{ $timer->end_day == 28 ? 'selected' : '' }} value="28">28</option>
                            <option {{ $timer->end_day == 29 ? 'selected' : '' }} value="29">29</option>
                            <option {{ $timer->end_day == 30 ? 'selected' : '' }} value="30">30</option>
                            <option {{ $timer->end_day == 31 ? 'selected' : '' }} value="31">31</option>
                        </select>
                    </span>
                </div>
            </div>



        </div>

        <div class="form-group bg-white clearfix p-10 m-r-25 m-l-25">
            <div class="col-md-4 col-sm-6 col-xs-6 col-md-offset-5 col-sm-offset-4" >
                <button class="btn btn-sm btn-primary " type="submit"> Update Time Schedule </button>
            </div>
        </div> 

    </form>

</div>