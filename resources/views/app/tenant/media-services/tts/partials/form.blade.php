
<form id="tts_form" name="13" action="" enctype='multipart/form-data' method='post'>
   
   {{csrf_field()}}

    <div class="col-md-12 col-sm-12 col-xs-12 " > 

        <div class="form-group clearfix">

            <label class="control-label checkbox-inline col-md-2 col-sm-3 col-xs-3 f-s-18"> Title </label>

            <div class="checkbox-inline">

                <input type="text" value="" class="form-control" name="title" required="required" />

            </div>

        </div>        

        <div class="form-group clearfix">

            <label class="control-label checkbox-inline col-md-2 col-sm-3 col-xs-3 f-s-18"> Type </label>

            <div class="checkbox-inline">

                <select class="form-control type" name="type">
                    <option value="greeting">Greeting</option>
                    <option value="menu">Menu</option>
                </select>

            </div>

        </div>

        <div class="form-group clearfix">

            <label class="control-label checkbox-inline col-md-2 col-sm-3 col-xs-3 f-s-18"> Text </label>

            <div class="checkbox-inline col-md-9 col-sm-9 col-xs-8">

                <textarea rows="3" name="text" required="required" class="form-control"></textarea>

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

