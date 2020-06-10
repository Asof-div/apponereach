<div class="modal fade edit-conference-modal" tabindex="1" role="dialog">
    <div class="modal-dialog " role="document">
        <div class="modal-content modal-lg">
            <div class="modal-header"> 
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
                <h5 class="modal-title"> <span class="h4"> Edit Conference </span> </h5> 
            </div>
            <div class="modal-body clearfix">
            	
	            <form id="edit_conference_form" class="form-horizontal" name="13" action="" enctype='multipart/form-data' method='post'>

				    
				    <div class="col-md-12 bg-white m-b-15 p-0 clearfix">

				        {{csrf_field()}}
				        <input type="hidden" name="conference_id" value="{{ $conference->id }}">
				        
				        <div class="col-md-12 col-sm-12">
				            <div class="form-group">
				                <label class="form-label col-md-4"> Name <i class="fa fa-asterisk text-danger"></i> </label>
				                <div class="col-md-8">
				                    <input type="text" name="name" class="form-control " value="{{ $conference->bridge_name }}">
				                </div>
				            </div>

				            <div class="form-group">
				                <label class="form-label col-md-4"> Type <i class="fa fa-asterisk text-danger"></i> </label>
				                <div class="col-md-8">
				                    <select name="type" class="form-control">
				                        <option {{ $conference->type == 'Local' ? 'selected' : '' }} value="Local">Local</option>
				                        @if(Gate::check('private_meeting_room') )
				                        <option {{ $conference->type == 'Private' ? 'selected' : '' }} value="Private">Private</option>
				                        @endif
				                    </select>
				                </div>
				            </div>           

				            <div class="form-group">
				                <label class="form-label col-md-4"> Conference no. <i class="fa fa-asterisk text-danger"></i> </label>
				                <div class="col-md-8">
				                    <input type="text" name="number" class="form-control " value="{{ $conference->number }}">
				                </div>
				            </div>
				            
				            @if(Gate::check('private_meeting_room') )            
				            <div class="form-group">
				                <label class="form-label col-md-4"> Moderator's Pin <i class="fa fa-asterisk text-danger"></i> </label>
				                <div class="col-md-8">
				                    <input type="text" name="moderator_pin" class="form-control" value="{{ $conference->admin_pin }}">
				                </div>
				            </div>
				            @endif

				            <div class="form-group">
				                <label class="form-label col-md-4"> Guest's Pin <i class="fa fa-asterisk text-danger"></i> </label>
				                <div class="col-md-8">
				                    <input type="text" name="user_pin" class="form-control" value="{{ $conference->guest_pin }}">
				                </div>
				            </div>


				            @if(Gate::check('private_meeting_room') )            
				            <div class="form-group clearfix ">
				                <label class="form-label col-md-4"> Auto Record </label>
				                <div class="col-md-8">
				                    <label class="switch">
				                        <input {{ $conference->record ? 'checked' : '' }} type="checkbox" name="auto_record">
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
				                        <input {{ $conference->wait_for_admin ? 'checked' : '' }} type="checkbox" name="wait">
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
				                        <input {{ $conference->annouce_join_leave ? 'checked' : '' }} type="checkbox" name="announce">
				                        <span class="slider "></span>
				                    </label>
				                </div>
				            </div>
				            @endif
				         
				            
				        </div>


				    </div>


					<div class="form-group bg-white clearfix m-r-15 m-l-0 m-t-10">
				        <div class="pull-right" >
				            <button class="btn btn-default" type="reset"> <i class="fa fa-exclamation-circle"></i> Cancel</button> &nbsp;
				            @if(Gate::check('local_conference') )            
				            <button class="btn btn-success" type="submit"> <i class="fa fa-save"></i> Update Conference </button>
				            @endif
				        </div>
				    </div>   
				  

					
				</form>

            </div>
        </div>
    </div>
</div>

<div class="modal fade delete-conference-modal" tabindex="1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form method="post" id="delete_conference_form" action="{{ route('tenant.conference.audio.delete', [$tenant->domain]) }}">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span> </button>
                    <h5 class="modal-title"> <span class="h4 text-primary"> DELETE CONFERENCE </span> </h5>
                </div>

                <div class="modal-body">
                 
                    {{ csrf_field() }}
                    <p class="f-s-15"> Are you sure you want to delete this ? </p>
                    <input type="hidden" name="conference_id" value="{{ $conference->id }}">
                     
                </div>

                <div class="modal-footer">
                    <div class="form-inline">
                        <div class="form-group m-r-10">
                            <button type="button" class="btn btn-warning" data-dismiss="modal"> NO </button>
                            <button type="submit" class="btn btn-primary"> YES </button>
                        </div>
                    </div>
                </div>

            </form>
        </div>            
    </div>
</div>