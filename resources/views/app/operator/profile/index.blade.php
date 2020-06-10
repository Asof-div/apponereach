@extends('layouts.operator_sidebar')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('operator.dashboard') }}"> Dashboard </a></li>
    <li class="breadcrumb-item active"> Profile </li>
@endsection

@section('title')
    Profile <small> User account settings </small>
@endsection
@section('content')



    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="card-title"> UPDATE PROFILE </div>
            </div>
            <div class="card-body">
                <form class="form-horizontal form-material" action="{{ route('operator.profile.store') }}" autocomplete="no"
                    method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="type" value="info">

                    <div class="form-group">
                        <label class="col-md-12">Last Name</label>
                        <div class="col-md-12">
                            <input type="text" name="lastname" placeholder="" class="form-control form-control-line" value="{{ Auth::user()->lastname }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">First Name</label>
                        <div class="col-md-12">
                            <input type="text" name="firstname" placeholder="" class="form-control form-control-line" value="{{ Auth::user()->firstname }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="example-email" class="col-md-12">Email</label>
                        <div class="col-md-12">
                            <input type="email" placeholder="" class="form-control form-control-line" name="email" value="{{ Auth::user()->email }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-12">Job Title</label>
                        <div class="col-md-12">
                            <input type="text"  class="form-control form-control-line" name="job_title" value="{{ Auth::user()->job_title }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <button class="btn btn-success" type="submit" >Update Profile</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6 ">

        <div class="card">
            <div class="card-header">
                <div class="card-title"> CHANGE PASSWORD </div>
            </div>
            <div class="card-body clearfix">

                <form class="form-horizontal form-material" action="{{ route('operator.profile.store') }}"
                    method="post" autocomplete="no">
                    {{ csrf_field() }}
                    <input type="hidden" name="type" value="password">

                    <div class="form-group">
                        <label class="col-md-12">Old Password</label>
                        <div class="col-md-12">
                            <input type="password" name="old_password" placeholder="" class="form-control form-control-line" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Password</label>
                        <div class="col-md-12">
                            <input type="password" name="password" placeholder="" class="form-control form-control-line" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Confirm Password</label>
                        <div class="col-md-12">
                            <input type="password" placeholder="" class="form-control form-control-line" name="password_confirmation" value="">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <button class="btn btn-success" type="submit" >Update Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title">CHANGE PROFILE PICTURE</div>
            </div>
            <div class="card-body">
                <center class="m-t-30"> <img src="{{ asset(Auth::user()->avatar) }}" class="rounded-circle" width="150" />
                    <h4 class="card-title m-t-10">{{ Auth::user()->name }}</h4>
                    <h6 class="card-subtitle">{{ Auth::user()->job_title }}</h6>

                </center>
            </div>
            <div> <hr> </div>
            <div class="card-body">

                <form class="form-horizontal form-material" action="{{ route('operator.profile.picture') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label class="col-md-12">Image</label>
                        <div class="col-md-12">
                            <input type="file" name="picture" placeholder="" class="form-control form-control-line" >
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <button class="btn btn-success" type="submit" >Upload Picture</button>
                        </div>
                    </div>
                </form>

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


        $mn_list = $('.page-sidebar-inner ul.sidebar-nav > li.menu-profile');
        $mn_list.addClass('active');


        let success = function(data){
            setTimeout(function(){
                window.location.reload();
            }, 3000);

            printFlashMsg(data.success);
            $('.upload-profile-modal').modal('hide');
            // setTimeout(window.location.reload(), 60000);

        }
        let failed = function(data){

        }

        $("body").on("submit", "#edit_name_form", function(e) {

            e.preventDefault();

            var url = "{{ route( 'operator.profile.store' )}}";

            // var form = $('#edit_name_form').serialize();
            formData = new FormData( document.getElementById('edit_name_form') );


            ajaxCall(url, formData, success, failed);

        });


        $("body").on("submit", "#edit_email_form", function(e) {

            e.preventDefault();

            var url = "{{ route( 'operator.profile.store' )}}";

            // var form = $('#edit_email_form').serialize();
            formData = new FormData( document.getElementById('edit_email_form') );


            ajaxCall(url, formData, success, failed);

        });


        $("body").on("submit", "#edit_password_form", function(e) {

            e.preventDefault();

            var url = "{{ route( 'operator.profile.store' )}}";

            // var form = $('#edit_password_form').serialize();
            formData = new FormData( document.getElementById('edit_password_form') );


            ajaxCall(url, formData, success, failed);

        });


        $("body").on("submit", "#edit_gender_form", function(e) {

            e.preventDefault();

            var url = "{{ route( 'operator.profile.store' )}}";

            // var form = $('#edit_gender_form').serialize();
            formData = new FormData( document.getElementById('edit_gender_form') );


            ajaxCall(url, formData, success, failed);

        });


        $("body").on("submit", "#edit_role_form", function(e) {

            e.preventDefault();

            var url = "{{ route( 'operator.profile.store' )}}";

            // var form = $('#edit_role_form').serialize();
            formData = new FormData( document.getElementById('edit_role_form') );


            ajaxCall(url, formData, success, failed);

        });

        $("body").on("submit", "#upload_profile_picture", function(e) {

            e.preventDefault();

            var url = "{{ route( 'operator.profile.picture' )}}";

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