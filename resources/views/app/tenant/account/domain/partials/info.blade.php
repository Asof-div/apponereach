<div class="table-responsive">
    <table class="table table-condensed table-striped">
        <tr>
            <td>Domain</td>
            <td>{{$tenant->domain}}</td>
        </tr>
        <tr>
            <td>Domain Account</td>
            <td>{{$tenant->info->email}}</td>
        </tr>
        <tr>
            <td>Status</td>
            <td>{{$tenant->status}}</td>
        </tr>
        <tr>
            <td>Last Update At</td>
            <td> {{$tenant->info->updated_at->format('D d, M Y : H:i')}} </td>
        </tr>
        <tr>
            <td>Last Update By</td>
            <td> <span>{{$tenant->info->updater->lastname .' '. $tenant->info->updater->firstname}} </span></td>
        </tr>

        
    </table>

    <hr class="horizonal-line-thick">
    
     <table class="table table-condensed table-striped">
        <form name="" id="tenant_info_form" >
        {{ csrf_field() }}
        <tr>
            <td> ID NO. </td>
            <td> <span class="">{{$tenant->info->id_no}} </span> </td>
        </tr>
        <tr>
            <td> ID TYPE. </td>
            <td> 
                <span class="tenant_field_text">{{$tenant->info->id_type}} </span> 
                <span class="tenant_field_input"> 
                    <input class="form-control" type="text" name="id_type" value="{{$tenant->info->id_type}}"> 
                </span>
            </td>
        </tr>
        <tr>
            <td> Bussiness Category </td>
            <td> 
                <span class="tenant_field_text">{{$tenant->info->customer_category}} </span> 
                <span class="tenant_field_input"> 
                    <input class="form-control" type="text" name="customer_category" value="{{$tenant->info->customer_category}}"> 
                </span>
            </td>
        </tr>
        <tr>
            <td> Address</td>
            <td> 
                <span class="tenant_field_text">{{ $tenant->info->address }} </span> 
                <span class="tenant_field_input"> 
                    <input class="form-control" type="text" name="address" value="{{ $tenant->info->address }}"/>
                </span> 
            </td>
        </tr>
        <tr>
            <td> State</td>
            <td> 
                <span class="tenant_field_text">{{$tenant->info->state ? $tenant->info->state : "N/A" }} </span> 
                <span class="tenant_field_input">
                    <input class="form-control" type="text" name="state" value="{{$tenant->info->state }}">
                </span>
            </td>
        </tr>
        <tr>
            <td> Nationality</td>
            <td> <span class="tenant_field_text"> {{$tenant->info->nationality }} </span> 
                <span class="tenant_field_input">
                    <input class="form-control" type="text" name="nationality" value="{{$tenant->info->nationality}}"/>
                </span> 
            </td>
        </tr>
        <tr>
            <td> Email </td>
            <td> 
                <span class="tenant_field_text">{{$tenant->info->email}} </span> 
                <span class="tenant_field_input"> 
                    <input class="form-control" type="text" name="email" value="{{$tenant->info->email}}"> 
                </span>
            </td>
        </tr>
        <tr>
            <td>Title</td>
            <td> 
                <span class="tenant_field_text">{{$tenant->info->title }} </span> 
                <span class="tenant_field_input">
                    <input class="form-control" type="text" name="title" value="{{$tenant->info->title }}">
                </span>
            </td>
        </tr>
        <tr>
            <td>First Name</td>
            <td> 
                <span class="tenant_field_text">{{$tenant->info->firstname }} </span> 
                <span class="tenant_field_input">
                    <input class="form-control" type="text" name="firstname" value="{{$tenant->info->firstname }}">
                </span>
            </td>
        </tr>
        <tr>
            <td>Last Name</td>
            <td> 
                <span class="tenant_field_text"> {{$tenant->info->lastname }} </span> 
                <span class="tenant_field_input"> 
                    <input class="form-control" type="text" name="lastname" value="{{$tenant->info->lastname }}"> 
                </span> 
            </td>
        </tr>
        <tr>
            <td>Middle Name</td>
            <td> 
                <span class="tenant_field_text"> {{$tenant->info->middlename }} </span> 
                <span class="tenant_field_input"> 
                    <input class="form-control" type="text" name="middlename" value="{{$tenant->info->middlename }}"> 
                </span> 
            </td>
        </tr>
        <tr>
            <td>Home No.</td>
            <td> 
                <span class="tenant_field_text"> {{$tenant->info->home_no }} </span> 
                <span class="tenant_field_input"> 
                    <input class="form-control" type="text" name="home_no" value="{{$tenant->info->home_no }}"> 
                </span> 
            </td>
        </tr>
        <tr>
            <td>Mobile No.</td>
            <td> 
                <span class="tenant_field_text"> {{$tenant->info->mobile_no }} </span> 
                <span class="tenant_field_input"> 
                    <input class="form-control" type="text" name="mobile_no" value="{{$tenant->info->mobile_no }}"> 
                </span> 
            </td>
        </tr>
        <tr>
            <td>Office No.</td>
            <td> 
                <span class="tenant_field_text"> {{$tenant->info->office_no }} </span> 
                <span class="tenant_field_input"> 
                    <input class="form-control" type="text" name="office_no" value="{{$tenant->info->office_no }}"> 
                </span> 
            </td>
        </tr>
        <tr>
            <td>Fax No.</td>
            <td> 
                <span class="tenant_field_text"> {{$tenant->info->fax_no }} </span> 
                <span class="tenant_field_input"> 
                    <input class="form-control" type="text" name="fax_no" value="{{$tenant->info->fax_no }}"> 
                </span> 
            </td>
        </tr>



        <tr>
            <td>
                <button type="button" class="btn btn-primary edit_info">Edit</button> 
                <button type="button" class="btn btn-warning cancel_info">Cancel</button>
            </td>
            {{-- @if(Gate::check('')) --}}
            <td><button type="submit" class="btn btn-success save_info">Save</button></td>
            {{-- @endif --}}
        </tr>

        </form>
    </table>

    <br>
    <hr style="background-color: #51BB8D; height: 3px;">
    <br>
</div>



<div class="panel panel-default box-shadow">
    <div class="panel-heading clearfix">Company Logo <button class="btn btn-primary pull-right edit_logo"><i class="fa fa-edit"></i></button></div>
    <div class="panel-body">
        
        
        <div class="col-md-6 col-sm-6 col-xs-6">
            
            <div class="form-group text-center">
                <img id="logo_img" class="img-rounded" src="{{URL::to($tenant->info->logo)}}" style="width: 100px; height: 100px;">
            </div>
            <div class="form-group tenant_logo_field" style="padding: 15px;">
                
                <button class="btn btn-warning" data-toggle="modal" data-target=".remove-logo-modal">Remove Logo</button>

            </div>
        </div>

        <div class="col-md-6 col-sm-6 col-xs-6 tenant_logo_field">  
            <div class="alert alert-danger alert-dismissable" id="errormessage" style="display:none">
                <button type="button" class="close close-alert"  aria-hidden="true">&times;</button>

                <div></div>

            </div>
            <form id="upload-business-logo" class="form" action="{{url($tenant->domain, 'business_logo')}}" method="post"  enctype="multipart/form-data">
                {{csrf_field()}}  
                <div class="form-group">
                    <label class="control-label"><span class="fa fa-image"></span>  Logo </label>
                    <input id="result_file" type="file" name="logo"  placeholder="" class="form-control">
                </div>
                

                <div class="form-group">
                    <button type="submit" class="btn btn-success upload-image"><span class="fa fa-exchange"></span> Change Logo</button>
                </div>
            </form>
        </div>
        
    </div>
</div>


