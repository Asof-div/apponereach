<div class="container_fluid">

    
    <form id="sound_form" name="13" action="" enctype='multipart/form-data' method='post'>
       
       {{csrf_field()}}

        <div class="col-md-12 col-sm-12 col-xs-12 " > 

            <div class="col-md-12 col-sm-12 col-xs-12 form-group">

                <label class="control-label col-md-4 col-sm-4 col-xs-4"> Name</label>

                <div class="col-md-4 col-sm-6 col-xs-8">

                    <input type="text" value="" class="form-control" name="name" required="required" />

                </div>

            </div>

            <div class="col-md-12 col-sm-12 col-xs-12 form-group">

                <label class="control-label col-md-4 col-sm-4 col-xs-4"> Type</label>

                <div class="col-md-4 col-sm-6 col-xs-8">

                    <select name="type" id="" class="form-control">
                        <option value="Normal">Normal</option>
                        <option value="IVR">IVR</option>
                        <option value="Voicemail">Voicemail</option>
                    </select>

                </div>

            </div>
            

            <div class="col-md-12 col-sm-12 col-xs-12 form-group">

                <label class="control-label col-md-4 col-sm-4 col-xs-4"> File Option </label>

                <div class="col-md-4 col-sm-6 col-xs-8">

                    <div class="radio">
                        <label class="form-check-label">
                            <input class="form-check-input" checked type="radio" name="file_option" id="file_option1" value="upload"> 
                            Upload A File
                        </label>
                    </div>

                    <div class="radio">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="file_option" id="file_option2" value="select"> 
                            Select From Your Music
                        </label>
                    </div>

                    <div class="radio">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="file_option" id="file_option3" value="phone"> 
                            Record Over Phone
                        </label>
                    </div>

                    

                </div>

            </div>

            <div class="col-md-12 col-sm-12 col-xs-12 form-group">

                <div class="file_option_cont " id="file_option_upload" > 
                    <label class="control-label col-md-4 col-sm-4 col-xs-4"> Upload MP3  </label>

                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <input type="file" name="file" id="" class="form-control">
                    </div>
                </div>
                <div class="file_option_cont hide" id="file_option_select"></div>
                <div class="file_option_cont hide" id="file_option_phone">

                    <label class="control-label col-md-4 col-sm-4 col-xs-4">  </label>

                    <div class="col-md-4 col-sm-6 col-xs-12">
                        Once you save this form, you will be given a phone number to call and a code to enter. This will allow you to record your message directly from the phone.
                    </div>

                </div>

            
            </div>



        </div>

        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
            <div class="col-md-4 col-sm-6 col-xs-6 col-md-offset-4 col-sm-offset-4" >
                <button class="btn btn-sm btn-primary " type="submit">Save Settings </button>
            </div>
        </div>



    </form>

</div>  