<div class="panel panel-with-tabs clearfix p-t-20 p-r-20 p-l-20" style="overflow-x: auto;">

    <form id="call_flow_form" class="form-horizontal" name="13" action="{{ route('tenant.media-service.timer.store', [$tenant->domain]) }}" enctype='multipart/form-data' method='post'>

        {{csrf_field()}}

        <input type="hidden" name="start_time" id="start_time" value="00:00">
        <input type="hidden" name="end_time" id="end_time" value="24:00">
        <input type="hidden" name="start_day" id="start_day">
        <input type="hidden" name="end_day" id="end_day">
        <input type="hidden" name="start_mon" id="start_mon">
        <input type="hidden" name="custom_day" id="custom_day">
        <input type="hidden" name="weekdays" id="weekdays" value="127">


        <div class="row" style="padding: 15px 25px;">

            <div class="form-group " style="border-bottom: 1px dashed #ccc; ">
                <label class="control-label col-md-3 f-s-15 f-w-300 m-t-20"> Strategy </label>
                <div class="col-md-9">
                    <label class="radio m-15">
                        <input type="radio" name="period" class="selected-period" checked="checked" value="all">
                        <span class="f-s-15">24 hours Open Office</span>
                        <p>Choose this option if you want incoming calls to be handles the same way all the time.</p>
                    </label>
                    <label class="radio m-15">
                        <input type="radio" name="period" class="selected-period" value="custom">
                        <span class="f-s-15">Custom Work Office Hours </span>
                        <p>Chose this option if you want incoming calls to be handles differently when your office is closed.</p>
                    </label>
                    <label class="radio m-15">
                        <input type="radio" name="period" class="selected-period" value="date">
                        <span class="f-s-15">Specific Date or Holiday </span>
                        <p>Choose this option if you want incoming calls to be handles differently on specific date, for example every 25th of December. </p>
                    </label>
                    <label class="radio m-15">
                        <input type="radio" name="period" class="selected-period" value="custom_date">
                        <span class="f-s-15">Custom Recurrent Date </span>
                        <p>Choose this option if you want incoming calls to be handles differently, every first monday of a particular month.</p>
                    </label>
                    <label class="radio m-15">
                        <input type="radio" name="period" class="selected-period" value="range">
                        <span class="f-s-15">Date Range </span>
                        <p>Choose this option if you want incoming calls to be handles differently between a specific date period. eg. 1nd - 5th January</p>
                    </label>
                </div>
            </div>

            <div class="form-group clearfix">
                <label class="control-label col-md-3 f-s-15 f-w-300"> Title </label>
                <div class="col-md-9">
                    <input type="text" name="title" class="form-control" required="required" value="{{ old('title') }}">
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
                        <input type="text" class="form-control time start" value="00:00">
                    </span>
                    <span class="checkbox-inline"> to </span>
                    <span class="checkbox-inline timebox">
                        <input type="text" class="form-control time end" value="24:00">
                    </span>
                </div>
            </div>


            <div class="form-group date-strategy strategy timepicker hide clearfix">

                <label class="control-label col-md-3 f-s-15 f-w-300"> <i class="fa fa-clock-o "></i> Specific Date & Time </label>
                <div class="col-md-9">
                    <span class="checkbox-inline">
                        <select class="form-control start_mon">
                            <option value="JAN">JAN</option>
                            <option value="FEB">FEB</option>
                            <option value="MAR">MAR</option>
                            <option value="APR">APR</option>
                            <option value="MAY">MAY</option>
                            <option value="JUN">JUN</option>
                            <option value="JUL">JUL</option>
                            <option value="AUG">AUG</option>
                            <option value="SEP">SEP</option>
                            <option value="OCT">OCT</option>
                            <option value="NOV">NOV</option>
                            <option value="DEC">DEC</option>
                        </select>
                    </span>
                    <span class="checkbox-inline">
                        <select class="form-control start_day">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                            <option value="24">24</option>
                            <option value="25">25</option>
                            <option value="26">26</option>
                            <option value="27">27</option>
                            <option value="28">28</option>
                            <option value="29">29</option>
                            <option value="30">30</option>
                            <option value="31">31</option>
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


            <div class="form-group custom-date-strategy strategy timepicker hide clearfix">

                <label class="control-label col-md-3 f-s-15 f-w-300"> <i class="fa fa-clock-o "></i> Custom Date </label>
                <div class="col-md-9">
                    <span class="checkbox-inline">
                        <select class="form-control start_mon">
                            <option value="JAN">JAN</option>
                            <option value="FEB">FEB</option>
                            <option value="MAR">MAR</option>
                            <option value="APR">APR</option>
                            <option value="MAY">MAY</option>
                            <option value="JUN">JUN</option>
                            <option value="JUL">JUL</option>
                            <option value="AUG">AUG</option>
                            <option value="SEP">SEP</option>
                            <option value="OCT">OCT</option>
                            <option value="NOV">NOV</option>
                            <option value="DEC">DEC</option>
                            <option value="ANY">ANY</option>
                        </select>
                    </span>
                    <span class="checkbox-inline">
                        <select class="form-control custom_day">
                            <option value="First">First</option>
                            <option value="Second">Second</option>
                            <option value="Third">Third</option>
                            <option value="Fourth">Fourth</option>
                            <option value="Firth">Firth</option>
                            <option value="Last">Last</option>

                        </select>
                    </span>
                    <span class="checkbox-inline">
                        <select class="form-control weekday">
                            <option value="2">MON</option>
                            <option value="4">TUE</option>
                            <option value="8">WED</option>
                            <option value="16">THU</option>
                            <option value="32">FRI</option>
                            <option value="64">SAT</option>
                            <option value="1">SUN</option>
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


            <div class="form-group date-range-strategy strategy timepicker hide clearfix">
                <label class="control-label col-md-3 f-s-15 f-w-300"> <i class="fa fa-clock-o "></i> Date Range </label>
                <div class="col-md-9">
                    <span class="checkbox-inline">
                        <select class="form-control start_mon">
                            <option value="JAN">JAN</option>
                            <option value="FEB">FEB</option>
                            <option value="MAR">MAR</option>
                            <option value="APR">APR</option>
                            <option value="MAY">MAY</option>
                            <option value="JUN">JUN</option>
                            <option value="JUL">JUL</option>
                            <option value="AUG">AUG</option>
                            <option value="SEP">SEP</option>
                            <option value="OCT">OCT</option>
                            <option value="NOV">NOV</option>
                            <option value="DEC">DEC</option>
                        </select>
                    </span>
                    <span class="checkbox-inline">
                        <select class="form-control start_day">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                            <option value="24">24</option>
                            <option value="25">25</option>
                            <option value="26">26</option>
                            <option value="27">27</option>
                            <option value="28">28</option>
                            <option value="29">29</option>
                            <option value="30">30</option>
                            <option value="31">31</option>
                        </select>
                    </span>
                    <span class="checkbox-inline timebox">
                        <input type="text" class="form-control time start">
                    </span>
                    <span class="checkbox-inline"> to </span>

                    <span class="checkbox-inline timebox">
                        <input type="text" class="form-control time end">
                    </span>
                    <span class="checkbox-inline">
                        <select class="form-control end_day">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                            <option value="24">24</option>
                            <option value="25">25</option>
                            <option value="26">26</option>
                            <option value="27">27</option>
                            <option value="28">28</option>
                            <option value="29">29</option>
                            <option value="30">30</option>
                            <option value="31">31</option>
                        </select>
                    </span>
                </div>
            </div>



        </div>

        <div class="form-group bg-white clearfix p-10 m-r-25 m-l-25">
            <div class="col-md-4 col-sm-6 col-xs-6 col-md-offset-5 col-sm-offset-4" >
                <button class="btn btn-sm btn-primary " type="submit"> Save Time Scheduler </button>
            </div>
        </div>

    </form>

</div>