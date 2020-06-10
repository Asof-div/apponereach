
<form id="sound_form" name="13" action="" enctype='multipart/form-data' method='post'>
   
   {{csrf_field()}}

    <div class="col-md-12 col-sm-12 col-xs-12 clearfix" > 

        <div class="form-group clearfix hide title-form">

            <label class="control-label checkbox-inline col-md-4 col-sm-4 col-xs-4 f-s-18"> Title </label>

            <div class="checkbox-inline col-md-4 col-sm-8 col-xs-8">

                <input type="text" value="" class="form-control" name="title" />

            </div>

        </div>        

        <div class="form-group clearfix">

            <label class="control-label checkbox-inline col-md-4 col-sm-4 col-xs-4"> File Option </label>

            <div class="checkbox-inline">

                <div class="radio-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" checked type="radio" name="file_option" id="file_upload" value="upload"> 
                        Upload A File
                    </label>
                </div>

                <div class="radio-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="file_option" id="record_over_phone" value="phone"> 
                        Record Over Phone
                    </label>
                </div>

            </div>

        </div>

        <div class="form-group clearfix">

            <div class="file_option_cont " id="file_option_upload" > 
                <label class="control-label checkbox-inline col-md-4 col-sm-4 col-xs-4"> Upload MP3  </label>

                <div class="checkbox-inline">
                    <input type="file" name="file" id="" class="form-control">
                </div>
            </div>
            <div class="file_option_cont hide" id="file_option_phone">

                <label class="control-label checkbox-inline col-md-4 col-sm-4 col-xs-4">  </label>

                <div class="checkbox-inline">
                    <p class="f-s-15">Once you save this form, you will be given a phone number to call and a code to enter. This will allow you to record your message directly from the phone.</p>
                </div>

            </div>

        
        </div>



    </div>

    <div class="col-md-12 col-sm-12 col-xs-12 form-group clearfix">
        <div class="pull-left">
            <button class="btn btn-sm btn-default " type="button" data-dismiss="modal" > &times; Close </button>
        </div>
        <div class="pull-right" >
            <button class="btn btn-sm btn-primary " type="submit"> <i class="fa fa-save"></i> Save Settings </button>
        </div>
    </div>



</form>

