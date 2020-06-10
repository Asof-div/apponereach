@extends('layouts.tenant_sidebar')

@section('breadcrumb')
    <li><a href="{{ url($tenant->domain,'dashboard') }}"> Dashboard </a></li>
    <li class="active"> Profile </li>
@endsection

@section('title')
    Profile <small> User account settings </small>
@endsection
@section('content')
    <div class="profile-container">
        <!-- begin profile-section -->
        <div class="profile-section">
            <!-- begin profile-left -->
            <div class="profile-left">
                <!-- begin profile-image -->
                <div class="profile-image">
                    <img id="profile_image" src="{{ asset(Auth::user()->avatar) }}" class="img-responsive" width="200" height="175" />
                    <i class="fa fa-user hide"></i>
                </div>
                <!-- end profile-image -->
                <div class="m-b-10">
                    <a href="#" data-toggle="modal" data-target=".upload-profile-modal" class="btn btn-warning btn-block btn-sm">Change Picture</a>
                </div>
        
            </div>
            <!-- end profile-left -->
            <!-- begin profile-right -->
            <div class="profile-right">
                <!-- begin profile-info -->
                <div class="profile-info">
                    
                    <div class="panel-group" id="accordion">
                        <div class="panel panel-primary panel-bg-ash overflow-hidden">
                            <div class="panel-heading pane-theme">
                                <h3 class="panel-title">
                                    <a class="accordion-toggle accordion-toggle-styled collapsed clearfix" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                        <i class="fa fa-cog pull-right"> <span> EDIT </span></i> 
                                        <div class="form-group">
                                            <span class="col-md-3 control-label">
                                                NAME
                                            </span>
                                            <span class="col-md-8 control-label">
                                                {{ Auth::user()->name }}
                                            </span>
                                        </div>
                                    </a>
                                </h3>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <form method="post" action="{{ route('tenant.account.profile.store', [$tenant->domain]) }}" id="edit_name_form">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="type" value="name">
                                        <div class="row m-b-15">
                                            <label class="control-label col-md-3"> First Name</label>
                                            <div class="col-md-9">
                                                <input type="text" name="firstname" class="form-control" value="{{ Auth::user()->firstname }}">
                                            </div>
                                        </div>
                                        <div class="row m-b-15">
                                            <label class="control-label col-md-3"> Last Name</label>
                                            <div class="col-md-9">
                                                <input type="text" name="lastname" class="form-control" value="{{ Auth::user()->lastname }}">
                                            </div>
                                        </div>
                                        <div class="row m-b-10 m-r-5 clearfix">
                                            <button class="btn btn-success pull-right" type="submit"><i class="fa fa-save"></i> Save Changes </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-success panel-bg-ash overflow-hidden">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <a class="accordion-toggle accordion-toggle-styled collapsed clearfix" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                        <i class="fa fa-cog pull-right"> <span> EDIT </span></i> 
                                        <div class="form-group">
                                            <span class="col-md-3 control-label">
                                                EMAIL
                                            </span>
                                            <span class="col-md-8 control-label">
                                                {{ Auth::user()->email }}
                                            </span>
                                        </div>
                                    </a>
                                </h3>
                            </div>
                            <div id="collapseTwo" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <form method="post" action="{{ route('tenant.account.profile.store', [$tenant->domain]) }}" id="edit_email_form">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="type" value="email">

                                        <div class="row m-b-15">
                                            <label class="control-label col-md-3"> Email</label>
                                            <div class="col-md-9">
                                                <input type="email" name="email" class="form-control" value="{{ Auth::user()->email }}">
                                            </div>
                                        </div>
                                        <div class="row m-b-10 m-r-5 clearfix">
                                            <button type="submit" class="btn btn-success pull-right"><i class="fa fa-save"></i> Save Changes </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-primary panel-bg-ash overflow-hidden">
                            <div class="panel-heading pane-theme">
                                <h3 class="panel-title">
                                    <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                                        <i class="fa fa-plus-circle pull-right">EDIT</span></i> 
                                        <div class="form-group">
                                            <span class="col-md-3 control-label">
                                                PASSWORD
                                            </span>
                                            <span class="col-md-8 control-label">
                                                ***********
                                            </span>
                                        </div>
                                    </a>
                                </h3>
                            </div>
                            <div id="collapseThree" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <form method="post" action="{{ route('tenant.account.profile.store', [$tenant->domain]) }}" id="edit_password_form">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="type" value="password">
                                        <div class="row m-b-15">
                                            <label class="control-label col-md-3">
                                            Old Password</label>
                                            <div class="col-md-9">
                                                <input type="password" name="old_password" class="form-control">
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row m-b-15">
                                            <label class="control-label col-md-3"> New Password</label>
                                            <div class="col-md-9">
                                                <input type="password" name="password" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="row m-b-15">
                                            <label class="control-label col-md-3"> Confirm Password</label>
                                            <div class="col-md-9">
                                                <input type="password" name="password_confirmation" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row m-b-10 m-r-5 clearfix">
                                            <button type="submit" class="btn btn-success pull-right"><i class="fa fa-save"></i> Save Changes </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-success panel-bg-ash overflow-hidden">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                                        <i class="fa fa-plus-circle pull-right"><span>EDIT</span></i> 
                                        <div class="form-group">
                                            <span class="col-md-3 control-label">
                                                GENDER
                                            </span>
                                            <span class="col-md-8 control-label">
                                                {{ Auth::user()->profile->gender }}
                                            </span>
                                        </div>
                                    </a>
                                </h3>
                            </div>
                            <div id="collapseFour" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <form method="post" action="{{ route('tenant.account.profile.store', [$tenant->domain]) }}" id="edit_gender_form">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="type" value="gender">
                                        <div class="row m-b-15">
                                            <label class="control-label col-md-3"> Gender</label>
                                            <div class="col-md-9">
                                                <div class="radio-inline">
                                                    <label>
                                                        <input type="radio" name="gender" value="Male" {{ Auth::user()->profile->gender == 'Male' ? 'checked' : ''}}/>
                                                        Male
                                                    </label>
                                                </div>
                                                <div class="radio-inline">
                                                    <label>
                                                        <input type="radio" name="gender" value="Female" {{ Auth::user()->profile->gender == 'Female' ? 'checked' : ''}} />
                                                        Female
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row m-b-10 m-r-5 clearfix">
                                            <button type="submit" class="btn btn-success pull-right"><i class="fa fa-save"></i> Save Changes </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-primary panel-bg-ash overflow-hidden">
                            <div class="panel-heading pane-theme">
                                <h3 class="panel-title">
                                    <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
                                        <i class="fa fa-plus-circle pull-right"><span>EDIT</span></i> 
                                        <div class="form-group">
                                            <span class="col-md-3 control-label">
                                                ROLE
                                            </span>
                                            <span class="col-md-8 control-label">
                                                {{ Auth::user()->profile->role }}
                                            </span>
                                        </div>
                                    </a>
                                </h3>
                            </div>
                            <div id="collapseFive" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <form method="post" action="{{ route('tenant.account.profile.store', [$tenant->domain]) }}" id="edit_role_form">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="type" value="role">
                                        
                                        <div class="row m-b-15">
                                            <label class="control-label col-md-3"> Role</label>
                                            <div class="col-md-9">
                                                <input type="text" name="role" class="form-control" value="{{ Auth::user()->profile->role }}">
                                            </div>
                                        </div>
                                        <div class="row m-b-10 m-r-5 clearfix">
                                            <button type="submit" class="btn btn-success pull-right"><i class="fa fa-save"></i> Save Changes </button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>


                    </div>

                </div>
                <!-- end profile-info -->
            </div>
            <!-- end profile-right -->
        </div>
        <!-- end profile-section -->
        <!-- begin profile-section -->
        <div class="profile-section">
            <!-- begin row -->
            <div class="row">
                <!-- begin col-4 -->
                <div class="col-md-4">
                    <h4 class="title"> Numbers <small> Extension And Phone Number</small></h4>
                    <!-- begin scrollbar -->
                    <div data-scrollbar="true" data-height="280px" class="bg-silver">
                        

                    </div>
                    <!-- end scrollbar -->
                </div>
                <!-- end col-4 -->
                <!-- begin col-4 -->
                <div class="col-md-4">
                    <h4 class="title">Group <small> Group </small></h4>
                    <!-- begin scrollbar -->
                    <div data-scrollbar="true" data-height="280px" class="bg-silver">
                        
                    </div>
                    <!-- end scrollbar -->
                </div>
                <!-- end col-4 -->
                <!-- begin col-4 -->
                <div class="col-md-4">
                    <h4 class="title">Todos <small> Top 10 </small></h4>
                    <!-- begin scrollbar -->
                    <div data-scrollbar="true" data-height="280px" class="bg-silver">
                                
                        <ul class="todolist">
                            @foreach(Auth::user()->todos as $todo)
                                <li>
                                    <a href="javascript:;" class="todolist-container {{ $todo->completed() ? 'active' : '' }}" data-click="todolist">
                                        <div class="todolist-input"><i class="fa fa-square-o"></i></div>
                                        <div class="todolist-title">{{ $todo->title }}</div>
                                    </a>
                                </li>

                            @endforeach
                        </ul>                       

                    </div>
                    <!-- end scrollbar -->
                </div>
                <!-- end col-4 -->
            </div>
            <!-- end row -->
        </div>
        <!-- end profile-section -->
    </div>

<div class="modal fade upload-profile-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close"> <span aria-hiddden="true">&times</span></button>
                <h4 class="modal-title"> Change Profile Picture </h4>
            </div>
            <div class="modal-body">
                <form id="upload_profile_picture" class="form" action="{{route('tenant.account.profile.picture', [$tenant->domain])}}" method="post"  enctype="multipart/form-data">
                    {{csrf_field()}}  
                    <div class="form-group">
                        <input id="result_file" type="file" name="picture"  placeholder="" class="form-control">
                    </div>
                    

                    <div class="form-group">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success upload-image"><span class="fa fa-user"></span> Upload </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection

@section('extra-css')

<style type="text/css">
    
    .panel-bg-ash{
        background-color: #F0F0F9 !important;
    }

</style>

@endsection

@section('extra-script')

    <script type="text/javascript" src="{{asset('js/domain_setting.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/custom_ajax/uploader.js')}}"></script>
    <script type="text/javascript">


        let success = function(data){
            printFlashMsg(data.success);
            setTimeout(function(){ 
                window.location.reload();
            }, 3000);  

           
            form.reset();
            $('#system_overlay').addClass('hidden');

            
        }
        let failed = function(data){

            $('#system_overlay').addClass('hidden');
            printErrorMsg(data.error);
        }

       
        $("body").on("submit", "#edit_name_form", function(e) {

            e.preventDefault();
            
            var url = "{{ route( 'tenant.account.profile.store', [$tenant->domain] )}}";
            
            // var form = $('#edit_name_form').serialize();   
            formData = new FormData( document.getElementById('edit_name_form') );
            
            
            ajaxCall(url, formData, success, failed);  
            
        });

       
        $("body").on("submit", "#edit_email_form", function(e) {

            e.preventDefault();
            
            var url = "{{ route( 'tenant.account.profile.store', [$tenant->domain] )}}";
            
            // var form = $('#edit_email_form').serialize();   
            formData = new FormData( document.getElementById('edit_email_form') );
            
            
            ajaxCall(url, formData, success, failed);  
            
        });

       
        $("body").on("submit", "#edit_password_form", function(e) {

            e.preventDefault();
            
            var url = "{{ route( 'tenant.account.profile.store', [$tenant->domain] )}}";
            
            // var form = $('#edit_password_form').serialize();   
            formData = new FormData( document.getElementById('edit_password_form') );
            
            
            ajaxCall(url, formData, success, failed);  
            
        });

       
        $("body").on("submit", "#edit_gender_form", function(e) {

            e.preventDefault();
            
            var url = "{{ route( 'tenant.account.profile.store', [$tenant->domain] )}}";
            
            // var form = $('#edit_gender_form').serialize();   
            formData = new FormData( document.getElementById('edit_gender_form') );
            
            
            ajaxCall(url, formData, success, failed);  
            
        });

       
        $("body").on("submit", "#edit_role_form", function(e) {

            e.preventDefault();
            
            var url = "{{ route( 'tenant.account.profile.store', [$tenant->domain] )}}";
            
            // var form = $('#edit_role_form').serialize();   
            formData = new FormData( document.getElementById('edit_role_form') );
            
            
            ajaxCall(url, formData, success, failed);  
            
        });

        $("body").on("submit", "#upload_profile_picture", function(e) {

            e.preventDefault();
            
            var url = "{{ route( 'tenant.account.profile.picture', [$tenant->domain] )}}";
            
            formData = new FormData( document.getElementById('upload_profile_picture') );
            
            let img = document.getElementById('profile_image');
            let input = document.getElementById("result_file");
            if ( window.FileReader ) {
                reader = new FileReader();
                reader.onloadend = function (e) { 

                    img.src = e.target.result;
                };
                reader.readAsDataURL( input.files[0] );
            }
            ajaxCall(url, formData, success, failed);  

        });

        $mn_list = $('.sidebar ul.nav > li.nav-edit-profile');
        $mn_list.addClass('active');
        
    </script>

@endsection