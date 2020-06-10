

    <form id="virtual_receptionist_form" class="form-horizontal" name="13" action="" enctype='multipart/form-data' method='post'>

        {{csrf_field()}}
        <input type="hidden" name="destination" value="" id="destination">

        <input type="hidden" name="ivr" value="" id="ivr">

        <input type="hidden" name="ivr_sound_path" class="ivr-sound-path" value="">

        <div class="col-md-7 col-sm-12 col-xs-12">

            <h4 class="f-w-600 text-primary"> Main Menu </h4>

            <div class="form-group m-r-15 m-l-5 m-t-10">
                <label class="f-s-14"> Name </label>
                <input type="text" name="name" value="" placeholder="Name" class="form-control">
            </div>


            <div class="clearfix">

                <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                    <label class="control-label col-md-3 f-s-15 f-w-300"> Key </label>
                    <div class="col-md-9">
                        <select class="form-control option-key">
                            <option value="1"> Press 1 </option>
                            <option value="2"> Press 2 </option>
                            <option value="3"> Press 3 </option>
                            <option value="4"> Press 4 </option>
                            <option value="5"> Press 5 </option>
                            <option value="6"> Press 6 </option>
                            <option value="7"> Press 7 </option>
                            <option value="8"> Press 8 </option>
                            <option value="9"> Press 9 </option>
                            
                        </select>                    
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                    <label class="control-label col-md-3 f-s-15 f-w-300"> Action </label>
                    <div class="col-md-9">
                        <select class="form-control destination-type">
                            <option selected="selected" value="" > Select Destination Type </option>
                            <option value="extension" >Ring On An Extension</option>
                            <option value="number" >Ring On A Number</option>
                            <option value="group">Ring A Group </option>
                            <option value="receptionist">Send To Virtual Receptionist </option>
                            <option value="conference">Join A Private Conference </option>
                            <option value="playback">Play a message </option>
                            <option value="voicemail">Send to voicemail </option>
                        </select>                    
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                    <label class="control-label col-md-3 f-s-15 f-w-300"> Destination </label>
                    <div class="col-md-9">
                        <select class="form-control destination">
    
                        </select>                    
                    </div>
                </div>
                
                <div class="col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3">
                    <a href="javascript:;" class="add-route"> <span class="f-s-14"> <i class="fa fa-plus"></i> Add Route (max 9) </span> </a>
                </div>

            </div>
                  
        </div>
        <div class="col-md-5 col-sm-12 col-xs-12 clearfix">

            <h4 class="f-w-600 text-primary"> Interactive Voice Response </h4>

            <div class="form-group">
                
                <label class="form-label f-s-13"> IVR MENU SOUND </label>
                <select class="form-control ivr-select-menu" name="ivr_menu">
                    <option value=""> &dash; &dash; &dash; Select IVR Message &dash; &dash; &dash; </option>
                    <optgroup label="Sound Files">
                        @foreach($sounds as $sound)
                            <option value="{{ "s".$sound->id }}" data-type="sound" data-text="{{ $sound->path }}">{{ $sound->title }}</option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Text To Speech">
                        @foreach($txttosp->where('mime_type', 'greeting') as $tts)
                            <option value="{{ "t".$tts->id }}" data-type="tts" data-text="{{ $tts->content }}">{{ $tts->title }}</option>
                        @endforeach
                    </optgroup>
                </select>
                <p class="clearfix ivr-menu-tts hidden">
                    <i style="background-color: #51BB8D; height: 2px; width: 100%; display: block; margin-top: 5px"></i>
                    <button type="button" class="btn-link edit-tts pull-right"> <i class="fa fa-pencil"></i> EDIT </button>
                    <span class="f-s-15 f-w-400 m-t-15"></span>
                    <textarea name="ivr_tts" class="form-control hidden" rows="3"></textarea>
                </p>
                <p class="clearfix ivr-menu-sound hidden p-10">
 
                </p>
            </div>


        </div>

        <div class="col-md-12 col-sm-12 col-xs-12">
            
            <div class="table-responsive">
                <table class="table ">
                    <thead>
                        <th>Key</th>
                        <th>Destination Type</th>
                        <th>Destination </th>
                        <th>Delete</th>
                    </thead>
                    <tbody class="ivr-menu">

                    </tbody>
                </table>
            </div>

        </div>

        <div class="clearfix"></div>

        <div class="form-group bg-white clearfix p-10 m-15 p-t-15">
            <span class="pull-left" > <button class="btn btn-sm btn-default " data-dismiss="modal" type="button"> Close </button> </span>
            <span class="pull-right" > <button class="btn btn-sm btn-primary " type="submit"> Save Virtual Receptionist </button> </span>
        </div> 

    </form>
