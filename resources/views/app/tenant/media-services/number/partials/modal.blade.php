<div class="modal fade features-number-modal" tabindex="1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span> </button>
                <h5 class="modal-title"> <span class="h4 text-primary"> ADVANCE FEATURES </span> </h5>
            </div>

            <div class="modal-body clearfix bg-silver">
                
                <div class="col-md-12 p-0">
                    <div class="col-md-12 clearfix">
                        <span class="selected-number f-s-18"></span>
                    </div>

                    <div class="col-md-4 p-2">
                        <div class="height-100 bg-white text-center">
                            <div class="text-center p-10">
                                <button class="btn btn-lg" data-toggle="modal" data-target=".scode-number-modal" data-dismiss="modal" ><i class="fa fa-2x fa-mobile"></i></button>
                            </div>
                            <div class="f-s-14 f-w-600">Short Code</div>
                        </div>
                    </div>

                    <div class="col-md-4 p-2">
                        <div class="height-100 bg-white text-center">
                            <div class="text-center p-10">
                                <button class="btn btn-lg" data-toggle="modal" data-target=".edit-number-modal" data-dismiss="modal" ><i class="fa fa-2x fa-user"></i></button>
                            </div>
                            <div class="f-s-14 f-w-600">Caller-ID Name</div>
                        </div>
                    </div>
                    <div class="col-md-4 p-2">
                        <div class="height-100 bg-white text-center">
                            <div class="text-center p-10">
                                <button class="btn btn-lg"><i class="fa fa-2x fa-sitemap"></i></button>
                            </div>
                            <div class="f-s-14 f-w-600">Find me, Follow me</div>
                        </div>
                    </div>
                    <div class="col-md-4 p-2">
                        <div class="height-100 bg-white text-center">
                            <div class="text-center p-10">
                                <button class="btn btn-lg"><i class="fa fa-2x fa-microphone"></i></button>
                            </div>
                            <div class="f-s-14 f-w-600">Call Recording</div>
                        </div>
                    </div>
                    <div class="col-md-4 p-2">
                        <div class="height-100 bg-white text-center">
                            <div class="text-center p-10">
                                <button class="btn btn-lg"><i class="fa fa-2x fa-inbox"></i></button>
                            </div>
                            <div class="f-s-14 f-w-600">Voicemail</div>
                        </div>
                    </div>
                    <div class="col-md-4 p-2">
                        <div class="height-100 bg-white text-center">
                            <div class="text-center p-10">
                                <button class="btn btn-lg" data-toggle="modal" data-target=".delete-number-modal" data-dismiss="modal" ><i class="fa fa-2x fa-trash"></i></button>
                            </div>
                            <div class="f-s-14 f-w-600">Delete</div>
                        </div>
                    </div>
                    
                
                </div>
                
            </div>

        </div>            
    </div>
</div>


<div class="modal fade delete-number-modal" tabindex="1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form method="post" id="delete_number_form" action="{{ route('tenant.media-service.number.delete', [$tenant->domain]) }}">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span> </button>
                    <h5 class="modal-title"> <span class="h4 text-primary"> DELETE NUMBER </span> </h5>
                </div>

                <div class="modal-body clearfix">
                    @include('partials.flash_message')
                    @include('partials.validation')
                    {{ csrf_field() }}
                    <p class="f-s-15"> Are you sure you want to delete this ? </p>
                    <input type="hidden" name="number_id" value="">
                    <input type="hidden" name="tenant_id" value="{{ $tenant->id }}">
                    <div class=" clearfix m-t-10">
                        <span class="selected-number f-s-16"></span>
                    </div> 
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


<div class="modal fade add-number-modal" tabindex="1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="post" id="number_form" action="{{ route('tenant.media-service.number.store', [$tenant->domain]) }}">

            <div class="modal-content">
                <div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> <h5 class="modal-title"> <span class="h4 text-primary"> ADD NUMBER TO CLOSED USER GROUP </span> </h5> </div>
                <div class="modal-body clearfix">
                    <div class="col-md-12 clearfix">
                        {{ csrf_field() }}
                        @include('partials.flash_message')
                        @include('partials.validation')
                        <input type="hidden" name="tenant_id" value="{{ $tenant->id }}">
                        <span class="text-danger"> NOTE !!! &nbsp; </span> 
                        <span >Package Limit {{ $tenant->package->msisdn_limit }} : To exceed this limit you will require additional charges </span>
                    </div>
                    <div class="col-md-12 clearfix">  
                        <div class="form-group ">
                            <label class="f-s-14">Display Name </label>
                            <input class="form-control" type="text" name="name" placeholder="John" required="required">
                        </div>
                        <div class="form-group ">
                            <label class="f-s-14">Phone Number </label>
                            <input class="form-control" name="number" placeholder="08032902922" type="text">
                        </div>

                        <div>
                            <label class="f-s-14">Assigned User </label>
                            <select class="form-control" name="user_id">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>             

                    </div>

                </div>

                <div class="modal-footer">
                    <div class="form-inline">
                        <div class="form-group m-r-10">
                            <button type="submit" class="btn btn-primary add-number-btn"> <i class="fa fa-save"></i> Add Number </button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

<div class="modal fade add-slot-number-modal" tabindex="1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="post" id="number_extra_form" action="{{ route('tenant.media-service.number.store', [$tenant->domain]) }}">

            <div class="modal-content">
                <div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> <h5 class="modal-title"> <span class="h4 text-primary"> ADD NUMBER TO CLOSED USER GROUP </span> </h5> </div>
                <div class="modal-body clearfix">
                    <div class="col-md-12 clearfix">
                        {{ csrf_field() }}
                        @include('partials.flash_message')
                        @include('partials.validation')
                        <input type="hidden" name="tenant_id" value="{{ $tenant->id }}">
                        <input type="hidden" name="slot" value="1">
                        <span class="text-danger"> NOTE !!! &nbsp; </span> 
                        <span >Package Limit {{ $tenant->package->msisdn_limit }} : To exceed this limit you will require additional charges </span>
                    </div>
                    <div class="col-md-12 clearfix">  
                        <div class="form-group ">
                            <label class="f-s-14">Display Name </label>
                            <input class="form-control" type="text" name="name" placeholder="John" required="required">
                        </div>
                        <div class="form-group ">
                            <label class="f-s-14">Phone Number </label>
                            <input class="form-control" name="number" placeholder="08032902922" type="text">
                        </div>

                        <div>
                            <label class="f-s-14">Assigned User </label>
                            <select class="form-control" name="user_id">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>             

                    </div>

                </div>

                <div class="modal-footer">
                    <div class="form-inline">
                        <div class="form-group m-r-10">
                            <button type="submit" class="btn btn-primary add-number-btn"> <i class="fa fa-save"></i> Add Number </button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>


<div class="modal fade edit-number-modal" tabindex="1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="post" id="update_number_form" action="{{ route('tenant.media-service.number.update', [$tenant->domain]) }}">

            <div class="modal-content">
                <div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> <h5 class="modal-title"> <span class="h4 text-primary"> EDIT NUMBER  </span> </h5> </div>
                <div class="modal-body clearfix">
                    <div class="col-md-12 clearfix">
                        {{ csrf_field() }}
                        @include('partials.flash_message')
                        @include('partials.validation')
                        <span class="text-danger"> NOTE !!! &nbsp; </span> 
                        <span >Package Limit {{ $tenant->package->msisdn_limit }} : To exceed this limit you will require additional charges </span>
                        <input type="hidden" name="number_id" value="">
                        <input type="hidden" name="tenant_id" value="{{ $tenant->id }}">

                    </div>
                    <div class="col-md-12 clearfix">  
                        <div class="form-group ">
                            <label class="f-s-14">Display Name </label>
                            <input class="form-control" type="text" name="name" placeholder="John" required="required">
                        </div>
                        <div class="form-group ">
                            <label class="f-s-14">Phone Number </label>
                            <input class="form-control" name="number" placeholder="08032902922" type="text">
                        </div>

                        <div>
                            <label class="f-s-14">Assigned User </label>
                            <select class="form-control" name="user_id">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>             

                    </div>

                </div>

                <div class="modal-footer">
                    <div class="form-inline">
                        <div class="form-group m-r-10">
                            <button type="submit" class="btn btn-success"> <i class="fa fa-save"></i> Update Number </button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

<div class="modal fade scode-number-modal" tabindex="1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="post" id="scode_number_form" action="{{ route('tenant.media-service.number.features', [$tenant->domain]) }}">

            <div class="modal-content">
                <div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> <h5 class="modal-title"> <span class="h4 text-primary"> INTERCOM SHORT CODE  </span> </h5> </div>
                <div class="modal-body clearfix">
                    <div class="col-md-12 clearfix">
                        {{ csrf_field() }}
                        @include('partials.flash_message')
                        @include('partials.validation')
                        
                        <input type="hidden" name="number_id" value="">
                        <input type="hidden" name="scode" value="scode">
                        <input type="hidden" name="scode_flow_id" value="">

                    </div>
                    <div class=" col-md-12 m-b-10 clearfix">
                        <span class="selected-number f-s-16"></span>
                    </div> 
                    <div class="col-md-12 clearfix">

                        <div class="form-group ">
                            <label class="f-s-14">Short Code Number </label>
                            <input class="form-control" name="scode" placeholder="922" type="text">
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <div class="form-inline">
                        <div class="form-group m-r-10">
                            <button type="submit" class="btn btn-success"> <i class="fa fa-save"></i> Save Short Code </button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>