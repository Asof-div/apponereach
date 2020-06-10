<div class="panel panel-with-tabs clearfix" >

    <form id="call_flow_form" class="form-horizontal" name="13" action="{{ route('tenant.media-service.inbox.update', [$tenant->domain]) }}" enctype='multipart/form-data' method='post'>

        {{csrf_field()}}
        
        <input type="hidden" name="inbox_id" value="{{ $inbox->id }}">
        <input type="hidden" name="sound_path" class="sound-path" value="{{ $inbox->sound_path }}">

        <div class="col-md-12 col-sm-12 col-xs-12 ">

            <div class="col-md-12 col-sm-12 col-xs-12 form-group">

                <label class="control-label col-md-3 checkbox-inline f-s-15 f-w-300"> Title </label>
                <div class="checkbox-inline col-md-6 col-sm-9">
                    <input type="text" name="title" placeholder="title" class="form-control" required="required" value="{{ old('title') ? old('title') : $inbox->title }}" />                    
                </div>

            </div>

            <div class="col-md-12 col-sm-12 col-xs-12 form-group">

                <label class="control-label col-md-3 f-s-15 f-w-300"> User ID & Pin</label>
                <div class="col-md-9">
                    <span class="checkbox-inline">
                    <input type="text" name="user" placeholder="20003" class="form-control" required="required" value="{{ old('user') ? old('user') : $inbox->user }}" />
                    </span>  
                    <span class="checkbox-inline">
                    <input type="text" name="pin" placeholder="2233" class="form-control" required="required" value="{{ old('pin') ? old('pin') : $inbox->pin }}" />  
                    </span>
                </div>

            </div>
  

            <div class="col-md-12 col-sm-12 col-xs-12 form-group">

                <label class="control-label col-md-3 checkbox-inline f-s-15 f-w-300"> Email </label>
                <div class="checkbox-inline col-md-6 col-sm-9">
                    <input type="text" name="email" placeholder="email" class="form-control" required="required" value="{{ old('email') ? old('email') : $inbox->email }}" />                    
                </div>

            </div>

            <div class="col-md-12 col-sm-12 col-xs-12 form-group">

                <label class="control-label col-md-5 f-s-15 f-w-300"> Send a copy to my mail </label>
                <label class="switch">
                    <input type="checkbox" name="send_to_mail" {{ $inbox->send_voicemail_to_mail ? 'checked' : '' }}>
                    <span class="slider round"></span>
                </label>

            </div>
            
            <div class="col-md-12 col-sm-12 col-xs-12 form-group">

                <label class="control-label col-md-3 checkbox-inline f-s-15 f-w-300"> <i class="fa fa-soundcloud "></i> Voicemail Prompt </label>
                <div class="checkbox-inline col-md-6 col-sm-9">

                    <select class="form-control voicemail-prompt" name="voicemail_prompt" >
                        <option value="" data-text=""> &dash; &dash; &dash; Select Voicemail Prompt &dash; &dash; &dash; </option>
                        @foreach($sounds as $sound)
                            <option {{ $inbox->voicemail_prompt_id == $sound->id ? 'selected' : '' }} value="{{ $sound->id }}" data-text="{{ $sound->path }}">{{ $sound->title }}</option>
                        @endforeach
                    </select>
                
                </div>

            </div>

            <div class="col-md-12 col-sm-12 col-xs-12 form-group">

                <label class="control-label col-md-5 f-s-15 f-w-300"> Save a copy of voice on the web portal </label>
                <label class="switch">
                    <input type="checkbox" name="web_portal" {{ $inbox->leave_copy_on_portal ? 'checked' : '' }}>
                    <span class="slider round"></span>
                </label>

            </div>

        </div>


        <div class="form-group bg-white clearfix p-10 m-r-25 m-t-20 m-l-25">
            <button class="btn btn-danger " type="cancel" data-dismiss="modal"> <i class="fa fa-times"></i> Cancel </button>

            <div class="pull-right" >
                <button class="btn btn-success " type="submit"> <i class="fa fa-save"></i> Update </button>
            </div>
        </div> 

    </form>
    
</div> 