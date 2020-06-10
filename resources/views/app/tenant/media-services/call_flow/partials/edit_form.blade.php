<div class="panel panel-with-tabs clearfix p-t-20" style="overflow-x: auto;">

    <form id="call_flow_form" class="form-horizontal" name="13" action="{{ route('tenant.media-service.call-flow.update', [$tenant->domain]) }}" enctype='multipart/form-data' method='post'>

        {{csrf_field()}}

        <input type="hidden" name="route_id" value="" > 
        <input type="hidden" name="destination_value" value="" class="destination_value">        

        
        <div class="col-md-12 col-sm-12 col-xs-12 ">

            <div class="col-md-12 col-sm-12 col-xs-12 form-group">

                <label class="control-label col-md-3 f-s-15 f-w-300"> Title </label>
                <div class="col-md-9">
                    <input type="text" name="title" placeholder="24 Hour Call Flow" class="form-control" required="required" value="" />                    
                </div>

            </div>


            <div class="col-md-12 col-sm-12 col-xs-12 form-group">

                <label class="control-label col-md-3 f-s-15 f-w-300"> <i class="fa fa-soundcloud "></i> Greetings Message </label>
                <div class="col-md-9">

                    <select class="form-control greeting-select" name="greeting" >
                        <option value=""> &dash; &dash; &dash; Select Playback Sound &dash; &dash; &dash; </option>
                        <optgroup label="Sound Files">
                            @foreach($sounds as $sound)
                                <option value="{{ "s".$sound->id }}" data-type="sound" data-text="{{ asset('storage/'.$sound->path) }}" data-path="{{ $sound->path }}">{{ $sound->title }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Text To Speech">
                            @foreach($txttosp->where('mime_type', 'greeting') as $tts)
                                <option value="{{ "t".$tts->id }}" data-type="tts" data-text="{{ $tts->content }}">{{ $tts->title }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                    <p class="greeting-tts hidden">
                        <i style="background-color: #51BB8D; height: 2px; width: 100%; display: block; margin-top: 5px"></i>
                        <button type="button" class="btn-link edit-tts pull-right"> <i class="fa fa-pencil"></i> EDIT </button>
                        <span class="f-s-15 f-w-400 m-t-15"></span>
                        <textarea name="greeting_tts" class="form-control hidden" rows="3">{{ old('greeting_tts') }}</textarea>
                    </p>
                
                </div>

            </div>

            @if(Gate::check('call_recording'))

            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                <label class="control-label col-md-3 f-s-15 f-w-300"> Record calls </label>
                <label class="m-l-15 switch">
                    <input type="checkbox" name="record">
                    <span class="slider round"></span>
                </label>
            </div>
            @endif

            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                <label class="control-label col-md-3 f-s-15 f-w-300"> Send to voicemail after no answer. </label>
                <label class="m-l-15 switch">
                    <input type="checkbox" name="voicemail">
                    <span class="slider round"></span>
                </label>
            </div>

            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                <label class="control-label col-md-3 f-s-15 f-w-300"> Select Destination Type </label>
                <div class="col-md-9">
                    <select name="destination_type" class="form-control destination-type">
                        @if(Gate::check('extension'))
                        <option value="extension">Ring On An Extension</option>
                        @endif
                        @if(Gate::check('intercom'))
                        <option value="number">Ring On A Number</option>
                        @endif
                        @if(Gate::check('group_call'))
                        <option value="group">Ring A Group </option>
                        @endif
                        @if(Gate::check('ivr'))
                        <option value="receptionist">Send To Virtual Receptionist </option>
                        @endif
                        @if(Gate::check('music_on_hold'))
                        <option value="playback">Play a message </option>
                        @endif
                        @if(Gate::check('call_recording'))
                        <option value="voicemail">Send to voicemail </option>
                        @endif
                    </select>                    
                </div>
            </div>

            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                <label class="control-label col-md-3 f-s-15 f-w-300"> Select Destination </label>
                <div class="col-md-9">
                    <select name="destination" class="form-control destination">
                        
                    </select>                    
                </div>
            </div>

            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                <label class="control-label col-md-3 f-s-15 f-w-300"> Ring time (sec) </label>
                <div class="col-md-9">
                    <input type="number" name="ring_time" class="form-control width-300" min="20" max="120" value="120" required="required">
                </div>
            </div>
        </div>

        <div class="clearfix">
            <div class="col-md-6 col-sm-8 col-xs-12 col-md-offset-3 col-sm-offset-2">
                <ul class="list-unstyled ">
                    <li class="m-b-5">
                        <input type="hidden" name="module" class="module" value="{{ old('module') }}">
                        <input type="hidden" name="sound_path" class="sound-path" value="{{ old('sound_path') }}">
                        <div class="text-center text-primary f-s-16"> Incoming Call Description </div>
                        <div class="text-center"> <i class="fa fa-long-arrow-down fa-2x"></i> </div>
                    </li>
                    <li class="call-flow-component text-black f-s-20">
                        <span class="checkbox-inline">Welcome Message : </span>
                        <span class="checkbox-inline text-primary f-s-15 playback-message"></span>
                    </li>                    
                </ul>
                <ul class="list-unstyled action-call">
                    
                </ul>

            </div>
        </div>

        @if(Gate::check('automated_call_routing'))

        <div class="form-group bg-white clearfix p-10 m-r-25 m-l-25">
            <span class="pull-left">
                <button class="btn btn-sm btn-default " type="button" data-dismiss="modal" aria-label="close"> Cancel </button>
            </span>
            <span class="pull-right">
                <button class="btn btn-sm btn-primary " type="submit"> Update Call Flow </button>
            </span>
        </div> 

        @endif

    </form>
    
</div> 