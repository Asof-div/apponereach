<div class="modal fade tts-configuration-modal" tabindex="1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header"> 
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
                <h5 class="modal-title"> <span class="h4 text-primary"> ADD NEW PLAY MEDIA TTS </span> </h5> 
            </div>
            <div class="modal-body clearfix">
                @include('partials.validation')
                @include('partials.flash_message')

                @include('app.tenant.media-services.tts.partials.form')
            </div>
        </div>
    </div>
</div>



<div class="modal fade edit-tts-modal" tabindex="1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header"> 
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
                <h5 class="modal-title"> <span class="h4 text-primary"> EDIT PLAY MEDIA TTS </span> </h5> 
            </div>
            <div class="modal-body clearfix">
                @include('partials.validation')
                @include('partials.flash_message')
                @include('app.tenant.media-services.tts.partials.edit_form')

            </div>
        </div>
    </div>
</div>




<div class="modal fade delete-tts-modal" tabindex="1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form id="tts_delete_form" method="post" action="{{ route('tenant.media-service.tts.delete', [$tenant->domain]) }}">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span> </button>
                    <h5 class="modal-title"> <span class="h4 text-primary"> DELETE TEXT TO SPEECH </span> </h5>
                </div>

                <div class="modal-body">
                        @include('partials.validation')
                        @include('partials.flash_message')    
                 
                    {{ csrf_field() }}
                    <p class="f-s-15"> Are you sure you want to delete this ? </p>
                    <input type="hidden" name="tts_id" value="">
                     
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