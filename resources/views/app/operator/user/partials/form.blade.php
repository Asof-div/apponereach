
<form id="user_form" name="13" class="form-horizontal" method="POST" action="{{ route('operator.user.store') }}">
    {{ csrf_field() }}


    <div class="p-20 col-md-12 bg-white m-b-15">
        <div class="clearfix m-b-20 m-l-15"> <span class="h4 text-primary"> User Details </span> </div>
        {{csrf_field()}}
        
        <div class="col-md-6 col-sm-6">


            <div class="form-group">
                <label class="form-label col-md-4">Lastname </label>
                <div class="col-md-8">
                    <input type="text" name="lastname" class="form-control" required="required" value="">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-md-4">Firstname </label>
                <div class="col-md-8">
                    <input type="text" name="firstname" class="form-control" required="required" value="">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-md-4">Job Title </label>
                <div class="col-md-8">
                    <input type="text" name="job_title" class="form-control" required="required" value="">
                </div>
            </div>
            
            
        </div>

        <div class="col-md-6 col-sm-6">


            <div class="form-group">
                <label class="form-label col-md-4"> Email </label>
                <div class="col-md-8">
                    <input type="text" name="email" class="form-control" required="required" value="">
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label col-md-4"> Password </label>
                <div class="col-md-8">
                    <input type="password" name="password" class="form-control" value="">
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label col-md-4">Confirm Password </label>
                <div class="col-md-8">
                    <input type="password" name="password_confirmation" class="form-control ">
                </div>
            </div>
    
            
        </div>


    </div>

    <div class="p-20 col-md-12 bg-white m-b-15">
        <div class="clearfix m-b-20 m-t-20 m-l-15"> <span class="h4 text-primary"> User Roles & Permissions </span> </div>
        <span></span>


        <div class="col-md-12 col-sm-12">

            @foreach($roles->chunk(3) as $roleChunk)
                <div class="row">
                    @foreach($roleChunk as $role)
                        <div class="col-md-4">
                            <label data-toggle="tooltip" data-placement="top" title="{{$role->description}} " class="checkbox" style="margin-left: 40px; margin-top: 5px; font-weight: 600; font-size: 15px;"> <input type="checkbox" name="roles[]" value="{{$role->id}}"> {{$role->label }}</label>
                        </div>
                    @endforeach
                </div>
            @endforeach
            
        </div>

    </div>

    @if(Gate::check('user.create'))
    <div class="col-md-12 bg-white clearfix p-15 m-t-20 m-r-0 m-l-0">
        <div class="pull-right" >
            <button class="btn btn-default" type="reset"> <i class="fa fa-exclamation-circle"></i> Cancel</button> &nbsp;
            <button class="btn btn-success" type="submit"> <i class="fa fa-save"></i> Create User </button>
        </div>
    </div>
    @else
        <div class="col-md-12 bg-danger clearfix m-t-30 p-15 m-r-0 m-l-0">
            <p class="f-s-16 p-10"> You do not have the permission to perform this action. Please Contact you Administrator.</p>
        </div>
    @endif

</form>

