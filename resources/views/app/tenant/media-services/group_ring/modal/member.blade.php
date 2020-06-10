<div class="modal fade" id="add_cug_member" tabindex="-1" role="dialog" aria-labelledby="hunt_memberLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
            <span class="h5 modal-title text-primary"> ADD MEMBERS </span>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">

                <div class="row" style="padding: 15px 25px;">


                    <div class="alert alert-danger alert-dismissable" id="errormessage" style="display:none">
                        <button type="button" class="close close-alert"  aria-hidden="true">&times;</button>

                        <div></div>

                    </div>
                

                    <div class="col-xs-12 col-md-12 form-group">

                        <label for=""> Select Phone Numbers </label>
                        
                        <ul id="available_cug" style="padding: 3px; list-style:none; border:1px solid #eee; border-radius:3px;">

                                                    
                        </ul>

                        <div class="text-center text-success"> <i class="fa fa-arrow-down"></i></div>
                    </div>

                </div>

                <div class="row" style="padding: 15px;">
                    <ul id="phone-container" style="padding: 3px; list-style:none; border:1px solid #eee; border-radius:3px;"> </ul>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" id="save_cug_changes" class="btn btn-primary">Save changes</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>

        
        </div>
    </div>
</div>
