
<form id="user_update_form" name="13" class="form-horizontal" method="POST" action="{{ route('tenant.account.user.update', [$tenant->domain]) }}">
    {{ csrf_field() }}

    <input type="hidden" name="user_id" value="{{ $user->id }}">

    <div class="form-group bg-white clearfix p-15 m-r-0 m-l-0">
        <div class="pull-right" >
            <button class="btn btn-default" type="reset"> <i class="fa fa-exclamation-circle"></i> Cancel</button> &nbsp;
            <button class="btn btn-primary" type="submit"> <i class="fa fa-save"></i> Update User </button>
        </div>
    </div>     
    
    <div class="p-20 col-md-12 bg-white m-b-15">
        <div class="clearfix m-b-15 m-l-15"> <span class="h4 text-primary"> User Details </span> </div>
        {{csrf_field()}}
        
        <div class="col-md-6 col-sm-6">


            <div class="form-group">
                <label class="form-label col-md-4">Lastname </label>
                <div class="col-md-8">
                    <input type="text" name="lastname" class="form-control" required="required" value="{{ $user->lastname }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-md-4">Firstname </label>
                <div class="col-md-8">
                    <input type="text" name="firstname" class="form-control" required="required" value="{{ $user->firstname }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-md-4">Job Title </label>
                <div class="col-md-8">
                    <input type="text" name="job_title" class="form-control" required="required" value="{{ $user->profile->role }}">
                </div>
            </div>
            
            
        </div>

        <div class="col-md-6 col-sm-6">


            <div class="form-group">
                <label class="form-label col-md-4"> Email </label>
                <div class="col-md-8">
                    <input type="text" name="email" class="form-control" required="required" value="{{ $user->email }}">
                </div>
            </div>
            

            
        </div>


    </div>

    <div class="p-20 col-md-12 bg-white m-b-15">
        <div class="clearfix m-b-15 m-l-15"> <span class="h4 text-primary"> User Roles & Permissions </span> </div>
        <span></span>

        <div class="col-md-12 col-sm-12">


            @foreach($roles->chunk(3) as $roleChunk)
                <div class="row">
                    @foreach($roleChunk as $role)
                        <div class="col-md-4">
                            <label class="checkbox" style="margin-left: 40px; margin-top: 5px; font-weight: 600; font-size: 15px;"> <input type="checkbox" {{ $user->hasRole($role->name) ? 'checked' : '' }} name="roles[]" value="{{$role->id}}"> {{$role->label }}</label>
                        </div>
                    @endforeach
                </div>
            @endforeach


        </div>

    </div>

</form>

