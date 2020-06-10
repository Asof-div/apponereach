<div class="table-responsive">
	
	<form id="call_flow_form" class="form-horizontal p-0" name="13" action="{{ route('tenant.media-service.pilot-line.voicemail', [$tenant->domain]) }}" enctype='multipart/form-data' method='post'>

        {{csrf_field()}}



        <div class="col-md-12 col-sm-12 col-xs-12 p-0 m-b-15 bg-white">

            <div class="p-l-20 p-b-10"> <h3 class="">Voicemail Settings </h3> </div>

            <div class="col-md-12 col-sm-12 col-xs-12 form-group">

                <label class="control-label col-md-5 f-s-15 f-w-300"> Send a copy to my mail </label>
                <label class="switch">
                    <input type="checkbox" name="send_to_mail">
                    <span class="slider round"></span>
                </label>

            </div>

            <div class="col-md-12 col-sm-12 col-xs-12 form-group">

                <label class="control-label col-md-3 col-sm-3 f-s-15 f-w-300"> Email </label>
                <div class="checkbox-inline col-md-6 col-sm-9 ">
                    <input type="text" name="email" placeholder="email" class="form-control" required="required" value="{{ old('email') }}" />                    
                </div>

            </div>
            
            <div class="col-md-12 col-sm-12 col-xs-12 form-group">

                <label class="control-label col-md-3 col-sm-3 f-s-15 f-w-300"> <i class="fa fa-soundcloud "></i> Voicemail Prompt </label>
                <div class="checkbox-inline col-md-6 col-sm-9">

                    <textarea class="form-control " rows="4" name="prompt"></textarea>
                
                </div>

            </div>

            <div class="col-md-12 col-sm-12 col-xs-12 form-group">

                <label class="control-label col-md-5 f-s-15 f-w-300"> Save a copy of voicemail on the web portal </label>
                <label class="switch">
                    <input type="checkbox" name="web_portal">
                    <span class="slider round"></span>
                </label>

            </div>

            <div class="col-md-12 col-sm-12 col-xs-12 form-group">

                <label class="control-label col-md-5 f-s-15 f-w-300"> Grant Voicemail Access To Only Admin User </label>
                <label class="switch">
                    <input type="checkbox" name="send_to_mail">
                    <span class="slider round"></span>
                </label>

            </div>

            <div class="col-md-12 col-sm-12 col-xs-12 form-group">

                <label class="control-label col-md-5 f-s-15 f-w-300"> Grant Voicemail Access To All Users </label>
                <label class="switch">
                    <input type="checkbox" name="send_to_mail">
                    <span class="slider round"></span>
                </label>

            </div>


            <div class="form-group bg-white clearfix p-10 m-r-25 m-l-25">
                <div class="col-md-4 col-sm-6 col-xs-6 col-md-offset-5 col-sm-offset-4" >
                    <button class="btn btn-sm btn-success " type="submit"> Save Voicemail Settings </button>
                </div>
            </div> 

        </div>



    </form>

</div>