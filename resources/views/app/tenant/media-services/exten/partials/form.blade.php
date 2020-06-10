<form id="extension_form" class="form-horizontal" name="13" action="" enctype='multipart/form-data' method='post'>

    {{csrf_field()}}

    <div class="col-md-8 col-sm-8 col-xs-12 col-md-offset-2 col-sm-offset-2">
        
        <div class="form-group clearfix">
            <label class="col-md-3 f-s-15 f-w-300 "> <i class="fa fa-asterisk f-s-12 text-danger"></i>Select User: </label>
            <div class="col-md-9">
                <select name="user_id" class="form-control">
                    <option value=""> &dash; Select User &dash; </option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group clearfix">

            <label class="col-md-3 f-s-15 f-w-300"> <i class="fa fa-asterisk f-s-12 text-danger"></i> Extension: </label>
            <div class="col-md-9">
                <input type="text" name="extension" placeholder="23400" class="form-control" required="required" />                    
            </div>

        </div>


        <div class="form-group clearfix">

            <label class="col-md-3 f-s-15 f-w-300"> Voicemail Status : </label>            

            <div class="col-md-9">
                <label class="switch checkbox-inline">
                    <input type="checkbox" name="voicemail" value="1" class="voicemail_toggle" />
                    <span class="slider round"></span>
                </label>
            </div>


        </div>
        

        <div class="form-group voicemail_body hidden clearfix">

            <label class="col-md-3 f-s-15 f-w-300">  <i class="fa fa-asterisk f-s-12 text-danger"></i> Voicemail Pin </label>

            <div class="col-md-9">
                <input type="text" name="voicemail_pin" placeholder="3245" class="form-control"  />  
            </div>
        </div>


        <div class="form-group">

            <label class="col-md-3 f-s-15 f-w-300"> Caller ID Name: </label>

            <div class="col-md-9">
                <input type="text" value="{{ old('name') }}" placeholder="{{ Auth::user()->name }}" class="form-control" name="name"/>
            </div>

        </div>

    </div>        

    <div class="col-md-8 col-sm-8 col-xs-12 col-md-offset-2 col-sm-offset-2">         
        <hr class="horizonal-line-thick">
        <div class="h4">ADVANCE INFO <span class="text-danger"> (At least one of the Primary Outbound DID/Secordary Outbound DID Must be filled once either of the permissions (9mobile/Other Networks/International) is granted.) </span> </div>
        <div class="clearfix m-t-20"></div>
        <div class="form-group clearfix">

            <label class="col-md-3 f-s-15 f-w-300"> Permit 9Mobile Line : </label>            

            <div class="col-md-9">
                <label class="switch">
                    <input type="checkbox" name="local_network_did" value="1" class="" />
                    <span class="slider round"></span>
                </label>
            </div>
        </div>

        <div class="form-group clearfix">
            
            <label class="col-md-3 f-s-15 f-w-300"> Permit Other Networks : </label>            

            <div class="col-md-9">
                <label class="switch">
                    <input type="checkbox" name="other_network_did" value="1" class="" />
                    <span class="slider round"></span>
                </label>
            </div>
        </div>

        <div class="form-group clearfix">
            
            <label class="col-md-3 f-s-15 f-w-300"> Pemit International Calls : </label>            

            <div class="col-md-9">
                <label class="switch">
                    <input type="checkbox" name="international_did" value="1" class="" />
                    <span class="slider round"></span>
                </label>
            </div>

        </div>

        <div class="form-group clearfix">
            
            <label class="col-md-3 f-s-15 f-w-300"> Primary Outbound DID: </label>            

            <div class="col-md-9">
                <select name="primary_outbound_did" class="form-control">
                    <option value=""> &dash;&dash;&dash; Select Primary Route &dash;&dash;&dash; </option>
                    @foreach($pilot_lines as $pilot_line)
                        <option value="{{ $pilot_line->number }}">{{ $pilot_line->number }}</option>
                    @endforeach
                </select>
            </div>

        </div>


        <div class="form-group clearfix">
            
            <label class="col-md-3 f-s-15 f-w-300"> Secondary Outbound DID: </label>            

            <div class="col-md-9">
                <select name="secondary_outbound_did" class="form-control">
                    <option value=""> &dash;&dash;&dash; Select Secondary Route &dash;&dash;&dash; </option>
                    @foreach($pilot_lines as $pilot_line)
                        <option value="{{ $pilot_line->number }}">{{ $pilot_line->number }}</option>
                    @endforeach
                </select>
            </div>

        </div>

    </div>

    <div class="col-md-12 clearfix">
        <div class="form-group clearfix p-10 m-r-15 m-l-15">

            <span class="pull-left"> 
                <button class="btn btn-sm btn-default " type="reset"> Cancel </button>
            </span>
            
            <span class="pull-right"> 
                <button class="btn btn-sm btn-success " type="submit"> <i class="fa fa-save"></i> Save Extension </button>
            </span>

        </div> 
    </div>
</form>

