

<form id="group_ring_form" name="13" action="" enctype='multipart/form-data' method='post' >
   
   {{csrf_field()}}

    <input type="hidden" name="members" value="" id="member_value">

    <div class="col-md-6 col-sm-8 col-xs-12 col-md-offset-3 col-sm-offset-2 clearfix" > 

        <div class="form-group clearfix">

            <label class="control-label checkbox-inline col-md-3 col-xs-4"> Name </label>

            <div class="checkbox-inline">

                <input type="text" value="" class="form-control" name="name" required="required" />

            </div>

        </div>


        <div class="form-group clearfix">

            <label class="control-label checkbox-inline col-md-3 col-xs-4"> Extension </label>

            <div class="checkbox-inline">

                <input type="text" value="" class="form-control" name="extension" required="required" />

            </div>

        </div>

        <div class="form-group clearfix">

            <label class="control-label checkbox-inline col-md-3 col-xs-4"> Ring Method </label>

            <div class="checkbox-inline">

                <select class="form-control" name="method">
                    <option value="Simultaneous"> Ring Simultaneously </option>
                    <option value="Sequence"> Ring Sequencially </option>
                </select>

            </div>

        </div>


    </div>

    <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">

        <br>
        
            <hr style="background-color: #51BB8D; height: 3px;">

        <br>

        <h5 class="h5"> Members (max 7)</h5>

        <table class="table table-condensed table-striped" id="table-members">
            <thead>
                <tr>
                    <th> Number </th>
                    <th style='width:10%;'> Action </th>
                </tr>
            </thead>
            <tbody>
            
            </tbody>
        </table>

    </div>


    <div class="col-md-12 col-sm-12 col-xs-12 form-group" style="margin-top: 25px">
        <span class="pull-left">
            <a href="javascript:;" data-toggle="modal" data-target="#add_cug_member" class="btn-link"> <i class="fa fa-plus-circle"></i> Add Member </a>
        </span>
        <span class="pull-right" >
            <button class="btn btn-sm btn-primary " type="submit">Save Group Ring </button>
        </span>
    </div>



</form>

