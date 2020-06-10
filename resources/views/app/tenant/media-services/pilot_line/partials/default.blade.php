<form action="" method="post" name="13" id="pilot_line_destination_form">
    
    {{csrf_field()}}
    <input type="hidden" name="pilot_line" value="{{ $pilot_line->id }}">

    <input type="hidden" name="sound_path" class="sound-path">
    <input type="hidden" name="destination" id="destination" value="{{ $pilot_line->destination }}">
    <input type="hidden" name="destination_type" id="destination_type" value="{{ $pilot_line->destination_type }}">
    <input type="hidden" name="destination_display" id="destination_display" value="{{ $pilot_line->destination_display }}">
    
    <div class="col-md-12 col-sm-12 col-xs-12"> 

        <div class="col-md-12 col-sm-12 col-xs-12 form-group" >        
                
            <label for="" class="control-label col-md-4 col-sm-4 col-xs-4 f-s-15"> Line Status : </label>

            <div class="col-md-4 col-sm-6 col-xs-6" > 
            
                <label class="switch">
                    <input type="checkbox" name="status" class="default_toggle" {{$pilot_line->status == 1 ? 'checked' : '' }}>
                    <span class="slider round"></span>
                </label>

            </div>


        </div>
    </div>

    <div class="col-md-12 col-sm-12 col-xs-12 default_dest_cont " > 

        <div class="col-md-12 col-sm-12 col-xs-12 form-group">

            <label class="control-label col-md-3 f-s-15 f-w-300"> <i class="fa fa-soundcloud "></i> Greetings Message </label>
            <div class="col-md-9">

                <select class="form-control greeting-select" name="greeting" >
                    <option value=""> &dash; &dash; &dash; Select Playback Sound &dash; &dash; &dash; </option>
                    <optgroup label="Sound Files">
                        @foreach($sounds as $sound)
                            <option value="{{ "s".$sound->id }}" {{$pilot_line->greeting == $sound->id ? 'selected' : '' }} data-type="sound" data-path="{{ $sound->path }}" data-text="{{ asset('storage/'.$sound->path) }}">{{ $sound->title }}</option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Text To Speech">
                        @foreach($txttosp->where('mime_type', 'greeting') as $tts)
                            <option value="{{ "t".$tts->id }}" {{$pilot_line->greeting == $tts->id ? 'selected' : '' }} data-type="tts" data-text="{{ $tts->content }}">{{ $tts->title }}</option>
                        @endforeach
                    </optgroup>
                </select>

                <p class="greeting-tts {{ $pilot_line->greeting_type == 'tts' ? '' : 'hidden' }}">
                    <i style="background-color: #51BB8D; height: 2px; width: 100%; display: block; margin-top: 5px"></i>
                    <button type="button" class="btn-link edit-tts pull-right"> <i class="fa fa-pencil"></i> EDIT </button>
                    <span class="f-s-15 f-w-400 m-t-15"></span>
                    <textarea name="greeting_tts" class="form-control hidden" rows="3">{{ $pilot_line->greeting_type == 'tts' ? $pilot_line->greeting_param : '' }}</textarea>
                </p>
                        
            </div>

        </div>

        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
            <label class="control-label col-md-3 f-s-15 f-w-300"> Record calls </label>
            <label class="m-l-15 switch">
                <input type="checkbox" name="record" {{$pilot_line->record == 1 ? 'checked' : '' }}>
                <span class="slider round"></span>
            </label>
        </div>


        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
            <label class="control-label col-md-3 f-s-15 f-w-300"> Send to voicemail after no answer. </label>
            <label class="m-l-15 switch">
                <input type="checkbox" name="voicemail" {{$pilot_line->voicemail == 1 ? 'checked' : '' }}>
                <span class="slider round"></span>
            </label>
        </div>

        <div class="clearfix">
            <div class="h4 text-center text-success"> Select Destination Final Destination </div>
        </div>
        <div class="destination-zone clearfix">
            <div class="col-md-4 ">

                <div id="destination-dropzone" class="dropzone clearfix">
                    <p class="double-tap-destination double-tap-dest-type">{{ $pilot_line->destination_label }}</p>
                    <p class="double-tap-destination double-tap-dest">{{ $pilot_line->destination_display }}</p>
                </div>
                 
            </div>


            <div class="col-md-8">
                <div class="destination-type-box clearfix">
                    <div class="h5">Click Destination</div>
                    <ul class="destination-type-selector">
                        <li data-destination='extension'>Ring On An Extension</li>
                        <li data-destination='number'>Ring On A Number</li>
                        <li data-destination='group'>Ring A Group</li>
                        <li data-destination='receptionist'>Direct To Virtual Receptionist</li>
                        <li data-destination='playback'>Play a message </li>
                        <li data-destination='voicemail'>Send To Voicemail</li>
                    </ul>
                </div>
                <div class="clearfix destination-box hidden">
                    <div class="destinations extension-destinations">
                        <div class="clearfix">
                            <button type="button" class="btn btn-default btn-xs btn-back-destination-type pull-right"> <i class="fa fa-backward"></i> back </button> <span class="text-success h4"> Extensions - Double-Click Destination</span>
                        </div>
                        <ul class="">
                            @foreach($extens as $exten)
                                <li class="dragable" data-destination_id="{{ $exten->id }}" data-destination_value="{{ $exten->number }}" data-type="extension" > {{ $exten->name . ' - ' . $exten->number }} </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="destinations number-destinations">
                        <div class="clearfix">
                            <button type="button" class="btn btn-default btn-xs btn-back-destination-type pull-right"> <i class="fa fa-backward"></i> back </button> <span class="text-success h4"> Numbers - Double-Click Destination</span>
                        </div>
                        <ul class="">
                            @foreach($numbers as $number)
                                <li class="dragable" data-destination_id="{{ $number->id }}" data-destination_value="{{ $number->number }}" data-type="number" > {{ $number->name . ' - ' . $number->number }} </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="destinations group-destinations">
                        <div class="clearfix">
                            <button type="button" class="btn btn-default btn-xs btn-back-destination-type pull-right"> <i class="fa fa-backward"></i> back </button> <span class="text-success h4"> Groups - Double-Click Destination</span>
                        </div>
                        <ul class="">
                            @foreach($groups as $group)
                                <li class="dragable" data-destination_id="{{ $group->id }}" data-type="group" > {{ $group->name . ' - ' . $group->number }} </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="destinations receptionist-destinations">
                        <div class="clearfix">
                            <button type="button" class="btn btn-default btn-xs btn-back-destination-type pull-right"> <i class="fa fa-backward"></i> back </button> <span class="text-success h4"> Virtual Receptionists - Double-Click Destination</span>
                        </div>
                        <ul class="">
                            @foreach($receptionists as $receptionist)
                                <li class="dragable" data-destination_id="{{ $receptionist->id }}" data-type="receptionist" > {{ $receptionist->name . ' - ' . $receptionist->number }} </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="destinations playback-destinations">
                        <div class="clearfix">
                            <button type="button" class="btn btn-default btn-xs btn-back-destination-type pull-right"> <i class="fa fa-backward"></i> back </button> <span class="text-success h4"> Playbacks - Double-Click Destination</span>
                        </div>
                        <ul class="">
                             @foreach($sounds as $playback)
                                <li class="dragable" data-destination_id="{{ $playback->id }}" data-type="playback" > {{ $playback->title }} </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="destinations voicemail-destinations">
                        <div class="clearfix">
                            <button type="button" class="btn btn-default btn-xs btn-back-destination-type pull-right"> <i class="fa fa-backward"></i> back </button> <span class="text-success h4"> Voicemails - Double-Click Destination</span>
                        </div>
                        <ul class="">
                            <li class="dragable" data-destination_id="{{ $pilot_line->id }}" data-type="voicemail" > {{ $pilot_line->number }} </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group bg-white clearfix p-10 m-r-25 m-l-25">
        <div class="col-md-4 col-sm-6 col-xs-6 col-md-offset-5 col-sm-offset-4" >
            <button class="btn btn-sm btn-primary " type="submit"> Save Default Call Flow </button>
        </div>
    </div> 

</form>

