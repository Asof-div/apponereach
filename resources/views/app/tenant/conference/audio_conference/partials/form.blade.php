<form id="conference_form" class="form-horizontal" name="13" action="" enctype='multipart/form-data' method='post'>

	<div class="form-group bg-white clearfix p-15 m-r-0 m-l-0 m-t-15 save-opportunity">
        <div class="pull-right" >
            <button class="btn btn-default" type="reset"> <i class="fa fa-exclamation-circle"></i> Cancel</button> &nbsp;
            @if(Gate::check('local_conference') )            
            <button class="btn btn-success" type="submit"> <i class="fa fa-save"></i> Save Conference </button>
            @endif
        </div>
    </div>   
  
    
    <div class="p-20 col-md-12 bg-white m-b-15 clearfix">

        {{csrf_field()}}
        
        <div class="col-md-12 col-sm-12">
            <div class="form-group">
                <label class="form-label col-md-4"> Name <i class="fa fa-asterisk text-danger"></i> </label>
                <div class="col-md-8">
                    <input type="text" name="name" class="form-control ">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-md-4"> Type <i class="fa fa-asterisk text-danger"></i> </label>
                <div class="col-md-8">
                    <select name="type" class="form-control">
                        <option value="Local">Local</option>
                        @if(Gate::check('private_meeting_room') )
                        <option value="Private">Private</option>
                        @endif
                    </select>
                </div>
            </div>           

            <div class="form-group">
                <label class="form-label col-md-4"> Conference no. <i class="fa fa-asterisk text-danger"></i> </label>
                <div class="col-md-8">
                    <input type="text" name="number" class="form-control " value="{{ $conf_num }}">
                </div>
            </div>
            
            @if(Gate::check('private_meeting_room') )            
            <div class="form-group">
                <label class="form-label col-md-4"> Moderator's Pin <i class="fa fa-asterisk text-danger"></i> </label>
                <div class="col-md-8">
                    <input type="text" name="moderator_pin" class="form-control" value="{{ $admin_pin }}">
                </div>
            </div>
            @endif

            <div class="form-group">
                <label class="form-label col-md-4"> Guest's Pin <i class="fa fa-asterisk text-danger"></i> </label>
                <div class="col-md-8">
                    <input type="text" name="user_pin" class="form-control" value="{{ $user_pin }}">
                </div>
            </div>

            @if(Gate::check('private_meeting_room') )            
            <div class="form-group clearfix ">
                <label class="form-label col-md-4"> Auto Record </label>
                <div class="col-md-8">
                    <label class="switch">
                        <input type="checkbox" name="auto_record">
                        <span class="slider "></span>
                    </label>
                </div>
            </div>          
            @endif

            @if(Gate::check('private_meeting_room') )            
            <div class="form-group">
                <label for="" class="form-label col-md-4 "> Wait For An Admin To Join </label >
                <div class="col-md-8">
                   <label class="switch">
                        <input type="checkbox" name="wait">
                        <span class="slider "></span>
                    </label>
                </div>
            </div>
            @endif

            @if(Gate::check('private_meeting_room') )            
            <div class="form-group">
                <label for="" class="form-label col-md-4 "> Announce When A Participant Join/Leave Conference </label>
                <div class="col-md-8">
                    <label class="switch">
                        <input type="checkbox" name="announce">
                        <span class="slider "></span>
                    </label>
                </div>
            </div>
            @endif
         
            
        </div>


    </div>

	
</form>