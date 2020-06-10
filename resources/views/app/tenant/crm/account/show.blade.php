@extends('layouts.tenant_sidebar')

@section('title')
    
    ACCOUNT

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.crm.index', [$tenant->domain]) }}"> CRM </a></li>
    <li><a href="{{ route('tenant.crm.account.index', [$tenant->domain]) }}"> Account </a></li>
    <li class="active">  </li>

@endsection

@section('content')

   
    <div class="col-md-12 col-sm-12 col-xs-12 p-0">
        <div class="panel panel-default">
            <div class="panel-heading"> 
                <div class="panel-title">
                    <span class="h3"> &nbsp; View Account  &nbsp;  <span class="text-primary pull-left">
                        <a href="{{ route('tenant.crm.account.create', [$tenant->domain]) }}" class="btn btn-default"> <i class="fa fa-plus-circle"></i> </a> </span>
                    </span> 
                    <span class="pull-right m-r-10">
                        <a href="{{ route('tenant.crm.account.index', [$tenant->domain]) }}" class="btn btn-lg btn-outline-default"> Account List </span> </a>
                    </span>
                    

                    <div class="panel-heading-btn m-r-10 m-t-10">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand" data-original-title="" title=""><i class="fa fa-expand"></i></a>
                    </div>
                </div>
                <hr class="horizonal-line-thick">
            </div>

            <div class="panel-body" style="min-height: 400px;">

                <div class="col-md-12 col-sm-12 col-xs-12 bg-silver">

                    @include('app.tenant.crm.account.partials.details')

                </div>

            </div>



        </div>
    </div>


    <div class="modal fade edit-account-modal" tabindex="1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header"> 
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
                    <h5 class="modal-title"> <span class="h4 text-primary"> EDIT ACCOUNT </span> </h5> 
                </div>
                <div class="modal-body clearfix">
                    @include('partials.validation')
                    @include('partials.flash_message')

                    @include('app.tenant.crm.account.partials.edit-form')
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade delete-account-modal" tabindex="1" role="dialog">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header"> 
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
                    <h5 class="modal-title"> <span class="h4 text-primary"> DELETE ACCOUNT </span> </h5> 
                </div>
                <div class="modal-body">
                    @include('partials.validation')
                    @include('partials.flash_message')                    
                </div>
            </div>
        </div>
    </div>

@endsection


@section('extra-script')

    <script>
       
       
        $mn_list = $('.sidebar ul.nav > li.nav-crm');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-account').addClass('active');

        $(function(){



        });

       

        $('body').on('submit', '#edit_account_form',  function(event){
            event.preventDefault();
            $('#system_overlay').removeClass('hidden');

            formData = new FormData(document.getElementById('edit_account_form'));

            url = "{{ route('tenant.crm.account.update', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location = data.url;
                }, 5000);  
                $('#system_overlay').addClass('hidden');

            }
            let failed = function(data){
                printErrorMsg(data.error);
                $('#system_overlay').addClass('hidden');
            }

            ajaxCall(url, formData, success, failed);  


        });



    
    </script>


@endsection

@section('extra-css')
        
    <style>


       .control-label{
            text-align : right;
            padding: 15px 15px 15px 25px;
        }

  
    </style>

@endsection