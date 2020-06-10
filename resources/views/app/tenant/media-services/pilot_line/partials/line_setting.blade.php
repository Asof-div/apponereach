<form action="" method="post" name="13" id="pilot_line_destination_form">
    
    {{csrf_field()}}
    <input type="hidden" name="pilot_line" value="{{ $pilot_line->id }}">

    <input type="hidden" name="sound_path" class="sound-path">
    
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

    <div class="col-md-12 col-sm-12 col-xs-12 default_dest_cont {{$pilot_line->status == 1 ? '': 'hide'}}" > 

        <div class="col-md-12 col-sm-12 col-xs-12 form-group">

            <label class="control-label col-md-3 f-s-15 f-w-300"> <i class="fa fa-soundcloud "></i> Greetings Message </label>
            <div class="col-md-9">

                <select class="form-control greeting-select" name="greeting" >
                    <option value=""> &dash; &dash; &dash; Select Playback Sound &dash; &dash; &dash; </option>
                    <optgroup label="Sound Files">
                        @foreach($sounds as $sound)
                            <option value="{{ "s".$sound->id }}" {{$pilot_line->greetings == $sound->id ? 'selected' : '' }} data-type="sound" data-path="{{ $sound->path }}" data-text="{{ asset('storage/'.$sound->path) }}">{{ $sound->title }}</option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Text To Speech">
                        @foreach($txttosp->where('mime_type', 'greeting') as $tts)
                            <option value="{{ "t".$tts->id }}" {{$pilot_line->greetings == $tts->id ? 'selected' : '' }} data-type="tts" data-text="{{ $tts->content }}">{{ $tts->title }}</option>
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

        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
            <label class="control-label col-md-3 f-s-15 f-w-300"> Select Destination Type </label>
            <div class="col-md-9">
                <select name="destination_type" class="form-control destination-type">
                    <option value="extension">Ring On An Extension</option>
                    <option value="number">Ring On A Number</option>
                    <option value="group">Ring A Group </option>
                    <option value="receptionist">Send To Virtual Receptionist </option>
                    <option value="conference">Join A Private Conference </option>
                    <option value="playback">Play a message </option>
                    <option value="voicemail">Send to voicemail </option>
                </select>                    
            </div>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
            <label class="control-label col-md-3 f-s-15 f-w-300"> Select Destination </label>
            <div class="col-md-9">
                <select name="destination" class="form-control destination">
                    <option value=""> &dash; &dash; &dash; Select Destination &dash; &dash; &dash; </option>
                    @foreach($extens as $exten)
                        <option value="{{ $exten->id }}" data-type="extension"> {{ $exten->number ." - " . $exten->name }} </option>
                    @endforeach
                </select>                    
            </div>
        </div>



        <div class="clearfix">
            <div class="col-md-6 col-sm-8 col-xs-12 col-md-offset-3 col-sm-offset-2">
                <ul class="list-unstyled ">
                    <li class="m-b-5">
                        <div class="text-center text-primary f-s-16"> Incoming Call Description </div>
                        <div class="text-center"> <i class="fa fa-long-arrow-down "></i> </div>
                    </li>
                    <li class="call-flow-component text-black f-s-20">
                        <div class="text-center"> Play Welcome Message </div>
                        @if($pilot_line->welcome)
                        <div class="text-justify text-primary p-10 p-l-15 f-s-15 playback-message"> {!! $pilot_line->greeting_type == 'tts' ? "<p class='tts-message'>" .nl2br($pilot_line->greeting_param) ."</p>" : "<div class='text-center'><audio src='". asset("storage/".$pilot_line->welcome->path) ."' controls ></audio></div>" !!} </div>
                        @else
                        <div class="text-warning"> No Welcome Message !!! </div>
                        @endif
                    </li>                    
                </ul>
         
                <ul class="list-unstyled action-call call-flow-order">
                    @if($destination->getType())

                        <li class="">
                            
                            <div class="text-center"> <i class="fa fa-long-arrow-down "></i> </div>
                            <div class="call-flow-component m-5 p-10 f-s-18" >
                               
                                <div class="text-center"> <i class="{{ $destination->getIcon() }} "></i> {{ $destination->getType() }} - {{ $destination->getName() }}  </div>
                                <table class="table table-striped">
                                @foreach($destination->getEndpoints() as $endpoint)
                                    <tr class="text-primary text-center">
                                        @if($endpoint['action'] != '')
                                        <td>{{ $endpoint['action'] }}</td>
                                        @endif
                                        <td>
                                        <i class="{{ $endpoint['icon'] }}"></i>
                                        <span>{{ $endpoint['number'] }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                                </table>
                            </div>
                        </li>

                    @else

                        <li class="f-s-15"> No Destination Configured </li>

                    @endif
                </ul>

            </div>
        </div>

    </div>

    <div class="form-group bg-white clearfix p-10 m-r-25 m-l-25">
        <div class="col-md-4 col-sm-6 col-xs-6 col-md-offset-5 col-sm-offset-4" >
            <button class="btn btn-sm btn-primary " type="submit"> Save Default Call Flow </button>
        </div>
    </div> 

</form>

