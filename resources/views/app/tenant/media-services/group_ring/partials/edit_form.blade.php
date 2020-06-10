

<form id="group_ring_update_form" name="13" action="" enctype='multipart/form-data' method='post' >
   
   {{csrf_field()}}

    <input type="hidden" name="members" value="" id="member_value">
    <input type="hidden" name="group_id" value="{{ $group->id }}">
    <input type="hidden" name="flow_id" value="{{ $group->call_flow_id }}">

    <div class="col-md-8 col-sm-8 col-xs-12 col-md-offset-2 col-sm-offset-2 clearfix" > 

        <div class="form-group clearfix">

            <label class="control-label checkbox-inline col-md-3 col-xs-4"> Name </label>

            <div class="checkbox-inline">

                <input type="text" value="{{ old('name') ? old('name') : $group->name }}" class="form-control" name="name" required="required" />

            </div>

        </div>


        <div class="form-group clearfix">

            <label class="control-label checkbox-inline col-md-3 col-xs-4"> Extension </label>

            <div class="checkbox-inline">

                <input type="text" value="{{ old('number') ? old('number') : $group->number }}" class="form-control" name="extension" required="required" />

            </div>

        </div>

        <div class="form-group clearfix">

            <label class="control-label checkbox-inline col-md-3 col-xs-4"> Ring Method </label>

            <div class="checkbox-inline">

                <select class="form-control" name="method">
                    <option selected="{{ $group->method == 'Simultaneous' ? 'selected' : '' }}" value="Simultaneous"> Ring Simultaneously </option>
                    <option selected="{{ $group->method == 'Simultaneous' ? 'selected' : '' }}" value="Sequence"> Ring Sequencially </option>
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
                @foreach($group->members as $member)
                    <tr>
                        @php $type = trim(strtolower($member['type'])) == 'number' ? 'fa fa-phone' : 'fa fa-tty'; @endphp
                        <td> 
                            <span style='padding:5px;'> {!! "<i class='". $type ."'> </i> &nbsp;  ". $member['number'] !!} </span> 
                            <input class='phone_ids' value="{{ isset($member['ids']) ? isset($member['ids']) : '' }}" name='ids[]' type='hidden' /> 
                            <input class='phone_value' value="{{ $member['number'] }}" name='phone[]' type='hidden' /> 
                            <input class='phone_type' value="{{ $member['type'] }}" name='type[]' type='hidden' /> 
                        </td>
                        <td> <button type='button' class='btn btn-warning btn-xs delete_member' >Delete</button> </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>


    <div class="col-md-12 col-sm-12 col-xs-12 form-group" style="margin-top: 25px">
        <span class="pull-left">
            <a href="javascript:;" data-toggle="modal" data-target="#add_cug_member" class="btn-link"> <i class="fa fa-plus-circle"></i> Add Member </a>
        </span>
        <span class="pull-right" >
            <button class="btn btn-sm btn-primary " type="submit">Update Group Ring </button>
        </span>
    </div>



</form>

