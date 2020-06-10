@extends('layouts.tenant_sidebar')


@section('title')
    
    Account Settings

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li class="active"> Account Settings </li>

@endsection

@section('content')

       
    <div class="col-md-12 col-sm-12 col-xs-12 p-0">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title p-t-20">
                    <span class="h3"> Domain Configuration </span>                   

                    <div class="panel-heading-btn m-r-10 m-t-10">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand" data-original-title="" title=""><i class="fa fa-expand"></i></a>
                    </div>
                </div>
                <hr class="m-t-5 m-b-0" style="background-color: #51BB8D; height: 3px;" />

            </div>


            <div class="panel-body bg-silver" style="min-height: 500px;">
                
                <div class="">

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-theme" role="tablist">

                        <li role="presentation" class="active"><a href="#business_info" aria-controls="business_info" role="tab" data-toggle="tab">BUSINESS INFORMATION </a></li>
                        
                        {{-- <li role="presentation"><a href="#terms" aria-controls="terms" role="tab" data-toggle="tab">TERMS AND CONDITION</a></li> --}}
      

                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content" style="padding:15px; ">

                        <div role="tabpanel" class="tab-pane active fadein" id="business_info">
                            
                            @include('app.tenant.account.domain.partials.info')

                        </div>

                        <div role="tabpanel" class="tab-pane fadein" id="terms">

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <div class="modal fade remove-logo-modal" tabindex="1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <form id="remove_logo_form" method="post" action="{{ route('tenant.account.domain.logo.delete', [$tenant->domain]) }}">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span> </button>
                        <h5 class="modal-title"> <span class="h4 text-primary"> DELETE LOGO </span> </h5>
                    </div>

                    <div class="modal-body">
                            @include('partials.validation')
                            @include('partials.flash_message')    
                     
                        {{ csrf_field() }}
                        <p class="f-s-15"> Are you sure you want to delete this ? </p>
                        <input type="hidden" name="tenant_id" value="{{ $tenant->id }}">
                         
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

        
@endsection


@section('extra-script')
    <script type="text/javascript" src="{{asset('js/domain_setting.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/custom_ajax/uploader.js')}}"></script>

    <script type="text/javascript">

        $mn_list = $('.sidebar ul.nav > li.nav-account');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-domain').addClass('active');

        var _token = "{{csrf_token()}}";
       


        $(".tenant_logo_field").hide();
        $(".tenant_field_input").hide();
        $(".cancel_info").hide();
        $(".save_info").hide();
        $(".edit_info").on('click', function(event){
            $(".cancel_info").show();
            $(".tenant_field_text").hide();
            $(".tenant_field_input").show();
            $(this).hide();
            $('.save_info').show();

        });

        $(".cancel_info").on('click', function(event){
            $(".edit_info").show();
            $(".tenant_field_text").show();
            $(".tenant_field_input").hide();
            $(this).hide();
            $('.save_info').hide();

        });

        $(".edit_logo").on('click', function(event){
            
            $(".tenant_logo_field").toggle();
        });


        $("#tenant_info_form").on('submit', function(event){
            event.preventDefault();

            let url = "{{route('tenant.account.domain.setting', [$tenant->domain])}}"; 

            formData = new FormData( document.getElementById("tenant_info_form") );
            let success = function(data){
                printFlashMsg(data.success);
                
            }
            let failed = function(data){

            }

            ajaxCall(url, formData, success, failed);  




        });

        $("#upload-business-logo").on("submit", function(e) {
            e.preventDefault();
            var extension = $('#result_file').val().split('.').pop().toLowerCase();
            if ($.inArray(extension, ['jpg', 'jpeg', 'png']) == -1) {
                $('#errormessage').find('div').html('Please Select Valid Image File... ');
                $("#errormessage").css('display','block');
                
                printErrorMsg(['Please Select Valid Image File... ']);
            } else {

                let url = "{{route('tenant.account.domain.logo', [$tenant->domain])}}"; 

                let form = document.getElementById("upload-business-logo");
                let img = document.getElementById("logo_img");
                let input = document.getElementById("result_file");
                let uploader = new Uploader(form);


                if ( window.FileReader ) {
                    reader = new FileReader();
                    reader.onloadend = function (e) { 

                        img.src = e.target.result;
                    };
                    reader.readAsDataURL( input.files[0] );
                }

                formData = new FormData( document.getElementById("upload-business-logo") );
                let success = function(data){
                    printFlashMsg(data.success);
                    let navlog = document.getElementById("navbar_logo");
                    navlog.src = img.src;

                    form.reset();
                    
                }
                let failed = function(data){

                }

                // logoAjax(formData, url);
                ajaxCall(url, formData, success, failed);  


                
                
            }
        });


        $("#remove_logo_form").on("submit", function(e) {
            e.preventDefault();
        
            let url = "{{route('tenant.account.domain.logo.delete', [$tenant->domain])}}"; 
            let img = document.getElementById("logo_img");
            formData = new FormData( document.getElementById("remove_logo_form") );

            let success = function(data){
                printFlashMsg(data.success);
                let navlog = document.getElementById("navbar_logo");

                navlog.src = '';
                img.src = '';
                $('.modal').modal('hide');
    
            }
            let failed = function(data){

            }

            ajaxCall(url, formData, success, failed);  

        });
    

    </script>


@endsection

@section('extra-css')
    
    <style type="text/css">



    </style>

@endsection