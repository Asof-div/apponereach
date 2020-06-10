<div class="modal fade gallery-configuration-modal" tabindex="1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header"> 
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
                <h5 class="modal-title"> <span class="h4 text-primary"> ADD PHOTO </span> </h5> 
            </div>
            <div class="modal-body clearfix">
                @include('partials.validation')
                @include('partials.flash_message')
                                
                <form id="gallery_form" name="13" action="" enctype='multipart/form-data' method='post'>
                   
                   {{csrf_field()}}

                    <div class="col-md-12 col-sm-12 col-xs-12 " > 

                        <div class="form-group clearfix">

                            <label class="control-label checkbox-inline col-md-2 col-sm-3 col-xs-3 f-s-18"> Title </label>

                            <div class="checkbox-inline">

                                <input type="text" value="" class="form-control" name="title" required="required" />

                            </div>

                        </div>        

                        <div class="form-group clearfix">

                            <label class="control-label checkbox-inline col-md-2 col-sm-3 col-xs-3 f-s-18"> Image </label>

                            <div class="checkbox-inline col-md-9 col-sm-9 col-xs-8">

                                <input type="file" value="" class="form-control" name="file" required="required" />

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
 
            </div>
        </div>
    </div>
</div>




<div class="modal fade delete-gallery-modal" tabindex="1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form id="gallery_delete_form" method="post" action="{{ route('tenant.media-service.gallery.delete', [$tenant->domain]) }}">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span> </button>
                    <h5 class="modal-title"> <span class="h4 text-primary"> DELETE IMAGE </span> </h5>
                </div>

                <div class="modal-body">
                 
                    {{ csrf_field() }}
                    <p class="f-s-15"> Are you sure you want to delete this ? </p>
                    <input type="hidden" name="gallery_id" value="">
                     
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