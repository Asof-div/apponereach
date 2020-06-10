<div class="container_fluid">

    <div class="col-md-12">
        <h4 class="h4 text-center" style="padding:10px 5px; "> Provisioning Configuration </h4>
    
            <form id="provisioning_form" name="13" action="" enctype='multipart/form-data' method='post'>
            
                {{csrf_field()}}

                <div class="col-md-12 col-sm-12 col-xs-12 " > 

                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">

                        <label class="control-label col-md-4 col-sm-4 col-xs-4"> Mac Address </label>

                        <div class="col-md-4 col-sm-6 col-xs-8">

                            <input type="text" value="" class="form-control" name="mac_address" required="required" />

                        </div>

                    </div>
                    

                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">

                        <label class="control-label col-md-4 col-sm-4 col-xs-4"> Server Address </label>

                        <div class="col-md-4 col-sm-4 col-xs-4">

                            <input type="text" name="server" id="" class="form-control" required="required" />                    

                        </div>

                    </div>


                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">

                        <label class="control-label col-md-4 col-sm-4 col-xs-4"> Phone Type </label>

                        <div class="col-md-4 col-sm-6 col-xs-8">

                            <select name="phone_type" id="" class="form-control">
                                <option value="Avaya"> Avaya </option>
                                <option value="Cisco">Cisco </option>
                                <option value="Grand_Screen"> Grand Screen </option>
                                <option value="Polycom"> Polycom </option>
                            </select>

                        </div>

                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">

                        <label class="control-label col-md-4 col-sm-4 col-xs-4"> Password </label>

                        <div class="col-md-4 col-sm-6 col-xs-8">

                            <input type="text" name="password" id="" class="form-control" required="required" />                    

                        </div>

                    
                    </div>

                    
                    <div class="col-md-12 col-sm-12 col-xs-12 form-group" style="margin-top: 25px">
                        <div class="col-md-4 col-sm-6 col-xs-6 col-md-offset-4 col-sm-offset-4" >
                            <button class="btn btn-sm btn-primary " type="submit"> Save Provisioning </button>
                        </div>
                    </div>


                </div>






            </form>


    </div>

    
</div> 