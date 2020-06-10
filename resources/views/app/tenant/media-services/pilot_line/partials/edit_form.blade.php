

    <form id="voicemail_settings_form" class="form-horizontal p-0" name="13" action="{{ route('tenant.media-service.pilot-line.voicemail', [$tenant->domain]) }}" enctype='multipart/form-data' method='post'>

        {{csrf_field()}}

        <input type="hidden" name="pilot_line" value="{{ $pilot_line->id }}">

        <div class="col-md-12 col-sm-12 col-xs-12 p-0 m-b-15 bg-white">

            <div class="p-l-20 p-b-10"> <h3 class="">Voicemail Settings </h3> </div>

            <div class="col-md-12 col-sm-12 col-xs-12 form-group">

                <label class="control-label col-md-5 f-s-15 f-w-300"> Send a copy to my mail </label>
                <label class="switch">
                    <input type="checkbox" {{ $pilot_line->send_voicemail_to_email ? 'checked' : ''  }} name="send_to_email" >
                    <span class="slider "></span>
                </label>

            </div>

            <div class="col-md-12 col-sm-12 col-xs-12 form-group">

                <label class="control-label col-md-3 col-sm-3 f-s-15 f-w-300"> Email </label>
                <div class="checkbox-inline col-md-6 col-sm-9 ">
                    <input type="text" name="email" placeholder="email" class="form-control" value="{{ $pilot_line->voicemail_email }}" />                    
                </div>

            </div>
            
            <div class="col-md-12 col-sm-12 col-xs-12 form-group">

                <label class="control-label col-md-5 f-s-15 f-w-300"> Save a copy of voicemail on the web portal </label>
                <label class="switch">
                    <input type="checkbox" {{ $pilot_line->save_voicemail ? 'checked' : '' }} name="web_portal">
                    <span class="slider "></span>
                </label>

            </div>



            <div class="form-group bg-white clearfix p-10 m-r-25 m-l-25">
                <div class="col-md-4 col-sm-6 col-xs-6 col-md-offset-5 col-sm-offset-4" >
                    <button class="btn btn-sm btn-success " type="submit"> Save Voicemail Settings </button>
                </div>
            </div> 

        </div>



    </form>

    <form id="recording_settings_form" class="form-horizontal p-0" name="13" action="{{ route('tenant.media-service.pilot-line.recording', [$tenant->domain]) }}" enctype='multipart/form-data' method='post'>

        {{csrf_field()}}
        <input type="hidden" name="pilot_line" value="{{ $pilot_line->id }}">
        <input type="hidden" name="start_time" id="start_time" value="{{ $pilot_line->recording_start }}">
        <input type="hidden" name="end_time" id="end_time" value="{{ $pilot_line->recording_end }}">
        <input type="hidden" name="weekdays" id="weekdays" value="{{ $pilot_line->recording_days }}">
        <div class="col-md-12 col-sm-12 col-xs-12 p-0 bg-white m-b-15">

            <div class="p-l-20 p-b-10"> <h3 class="">Call Recording Settings </h3> </div>


            <div class="form-group ">
                <label class="control-label col-md-3 f-s-15 f-w-300 m-t-20 p-l-30"> Record </label>
                <div class="col-md-9 p-l-30">    
                
                    <label class="radio m-15">
                        <input type="radio" name="period" class="selected-period" {{ $pilot_line->recording_period == 'all' ? 'checked' : '' }} value="all">
                        <span class="f-s-15">24 hours call Recording</span>
                        <p>Choose this option if you want all incoming calls to be recorded at all times.</p>
                    </label>
                    <label class="radio m-15">
                        <input type="radio" name="period" class="selected-period" {{ $pilot_line->recording_period == 'custom' ? 'checked' : '' }} value="custom">
                        <span class="f-s-15">Custom prefered Hours </span>
                        <p>Choose this option if you want incoming calls to be recorded between specific period and specific weekdays .</p>
                    </label>

                </div>
                <div class="m-r-15 m-l-15 clearfix" style="border-bottom: 1px dashed #ccc; "> </div>

            </div>

            <div class="form-group hour-strategy strategy clearfix">

                <label class="control-label col-md-3 f-s-15 f-w-300 p-l-30"> <i class="fa fa-soundcloud "></i> Day Of Week </label>
                <div class="col-md-9 p-l-30">

                    <label class="day_of_week checkbox-inline" >
                        <input type="checkbox" checked="checked" class="day-of-week" name="day_of_week[]" value="1">
                        SUN
                    </label>

                    <label class="day_of_week checkbox-inline" >
                        <input type="checkbox" checked="checked" class="day-of-week" name="day_of_week[]" value="2">
                        MON
                    </label>
                    
                    <label class="day_of_week checkbox-inline" >
                        <input type="checkbox" checked="checked" class="day-of-week" name="day_of_week[]" value="4">
                        TUE
                    </label>

                    <label class="day_of_week checkbox-inline" >
                        <input type="checkbox" checked="checked" class="day-of-week" name="day_of_week[]" value="8">
                        WED
                    </label>

                    <label class="day_of_week checkbox-inline" >
                        <input type="checkbox" checked="checked" class="day-of-week" name="day_of_week[]" value="16">
                        THU
                    </label>

                    <label class="day_of_week checkbox-inline" >
                        <input type="checkbox" checked="checked" class="day-of-week" name="day_of_week[]" value="32">
                        FRI
                    </label>

                    <label class="day_of_week checkbox-inline" >
                        <input type="checkbox" checked="checked" class="day-of-week" name="day_of_week[]" value="64">
                        SAT 
                    </label>

                    <div class="m-t-15">          
                        <span class="checkbox-inline timebox">
                            <input type="text" class="form-control time start" value="00:00">
                        </span>
                        <span class="checkbox-inline"> to </span>
                        <span class="checkbox-inline timebox">
                            <input type="text" class="form-control time end" value="24:00">
                        </span>
                    </div>

                </div>
            </div>

            <div class="form-group bg-white clearfix p-10 m-r-25 m-l-25">
                <div class="col-md-4 col-sm-6 col-xs-6 col-md-offset-5 col-sm-offset-4" >
                    <button class="btn btn-sm btn-success " type="submit"> Save Recording Settings </button>
                </div>
            </div> 

        </div>



    </form>