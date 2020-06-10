@extends('layouts.operator_sidebar')

@section('title')
    
    USER MANAGEMENT

@endsection

@section('breadcrumb')

    <li ><a href="{{ route('operator.dashboard') }}"> Dashboard </a></li>
    <li class="active"> Users </li>

@endsection

@section('content')


    <div class="col-md-12 col-sm-12 col-xs-12 no-p">
        <div class="panel ">
            <div class="panel-heading"> 
                <div class="panel-title pt-2">
                    <span class="h3"> &nbsp; User List ({{ $operators->count() }}) 
                    </span>
                </div> 
                <span class="pull-right mr-2">
                    <a href="{{ route('operator.user.create') }}" class="btn btn-success"> <i class="fa fa-plus-circle"></i> New User &nbsp; <span class="text-primary"> </span> </a>
                </span>

            </div>

            <div class="panel-body" style="min-height: 400px;">

                <hr style="background-color: #51BB8D; height: 3px;" />

                <div class="col-md-12 col-sm-12 col-xs-12 p-t-15 p-b-15 bg-silver">

                    @include('app.operator.user.partials.table')

                </div>

            </div>



        </div>
    </div>

    <div class="modal fade" id="edit_user_modal" tabindex="1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" > &times; </button>
                    <h5 class="modal-title"> Edit User Details </h5>
                </div>
                <div class="modal-body clearfix">
                    
                    <form id="user_form" name="13" class="form-horizontal form-material" method="POST" action="{{ route('operator.user.update') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="user_id" value="">
                        <div class="form-group">
                            <label class="form-label col-md-12">Lastname </label>
                            <div class="col-md-12">
                                <input type="text" name="lastname" class="form-control" required="required" value="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label col-md-12">Firstname </label>
                            <div class="col-md-12">
                                <input type="text" name="firstname" class="form-control" required="required" value="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label col-md-4">Job Title </label>
                            <div class="col-md-12">
                                <input type="text" name="job_title" class="form-control" required="required" value="">
                            </div>
                        </div>
                        

                        <div class="form-group">
                            <label class="form-label col-md-4"> Email </label>
                            <div class="col-md-12">
                                <input type="text" name="email" class="form-control" required="required" value="">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12 clearfix">
                                <button type="button" class="btn btn-danger pull-left" data-dismiss="modal" > Close </button>
                                <button type="submit" class="btn btn-success pull-right"> <i class="fa fa-save"></i> Update </button>
                            </div>
                        </div>
                        

                    </form>

          
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delete_user_modal" tabindex="1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"> &times; </button>
                    <h5 class="modal-title">Delete User </h5>
                </div>
                <div class="modal-body">
                    
                    <form id="user_form" name="13" class="form-horizontal form-material" method="POST" action="{{ route('operator.user.delete') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="user_id" value="">
                        <div class="form-group">
                            <div class="col-md-12 f-s-17">
                                Be sure you really want to delete this user. This action is re-reverseble .
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <div class="col-md-6">
                                <button class="btn btn-primary" type="cancel" data-dismiss="modal"> Cancel </button>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-success pull-right"> Delete </button>
                            </div>
                        </div>
                        

                    </form>

                </div>
            </div>
        </div>
    </div>


@endsection


@section('extra-script')    

    <script>
       
        $mn_list = $('.page-sidebar-inner ul.sidebar-nav > li.user');
        $mn_list.addClass('active open');
        $mn_list.find('.sub-menu > .user-index').addClass('active');

        $('#edit_user_modal').on('show.bs.modal', function(evt){
           let button = $(evt.relatedTarget);
           $(this).find('[name="firstname"]').val(button.attr('data-firstname'));
           $(this).find('[name="lastname"]').val(button.attr('data-lastname'));
           $(this).find('[name="email"]').val(button.attr('data-email'));
           $(this).find('[name="user_id"]').val(button.attr('data-id'));
           $(this).find('[name="job_title"]').val(button.attr('data-title'));


        });

        $('#delete_user_modal').on('show.bs.modal', function(evt){
            let button = $(evt.relatedTarget);
            $(this).find('[name="user_id"]').val(button.attr('data-id'));
        });
    
    </script>


@endsection

@section('extra-css')

    <style>
       
        ul.nav.nav-pills li.active a{
            background: #51BB8D !important;
            color: #fff !important;
            font-weight: bold;
        }

    </style>

@endsection