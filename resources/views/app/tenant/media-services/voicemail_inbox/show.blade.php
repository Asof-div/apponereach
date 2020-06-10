@extends('layouts.tenant_sidebar')

@section('title')
    
    VOICEMAIL BOX

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.media-service.index', [$tenant->domain]) }}"> Media Services </a></li>
    <li><a href="{{ route('tenant.media-service.inbox.index', [$tenant->domain]) }}"> Voicemail Box </a></li>
    <li class="active"> View Voicemail Inbox </li>

@endsection

@section('content')
    
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading"> 
                <div class="panel-title p-b-10 p-t-10">
                    <span class="h3"> 
                        Show Inbox 
                    </span> 
                    <span class="pull-right m-r-10">
                        <a href="{{ route('tenant.media-service.inbox.index', [$tenant->domain]) }}" class="btn btn-lg btn-outline-default"> Voicemail Box </a>
                    </span>
                    

                    <div class="panel-heading-btn m-r-10 m-t-10">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand" data-original-title="" title="">
                            <i class="fa fa-expand"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="panel-body" style="min-height: 400px;">

                <hr style="background-color: #51BB8D; height: 3px;" />

                <div class="col-md-12 col-sm-12 col-xs-12 p-t-15 bg-silver">

                    @include('app.tenant.media-services.voicemail_inbox.partials.details')

                </div>

            </div>



        </div>
    </div>
    
    <div class="modal fade edit-inbox-modal" tabindex="1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-lg">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span> </button>
                    <h5 class="modal-title"> <span class="h4 text-primary"> EDIT VOICEMAIL BOX </span> </h5>
                </div>

                <div class="modal-body">
                 
                    @include('app.tenant.media-services.voicemail_inbox.partials.edit_form')
                                             
                </div>

            </div>            
        </div>
    </div>

    <div class="modal fade delete-inbox-modal" tabindex="1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content ">

                <form method="post" action="{{ route('tenant.media-service.inbox.delete', [$tenant->domain]) }}">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span> </button>
                        <h5 class="modal-title"> <span class="h4 text-primary"> DELETE VOICEMAIL BOX </span> </h5>
                    </div>

                    <div class="modal-body">
                     
                        {{ csrf_field() }}
                        <p class="f-s-15"> Are you sure you want to delete this ? </p>
                        <input type="hidden" name="inbox_id" value="{{ $inbox->id }}">
                         
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
    
    <script type="text/javascript">
        $mn_list = $('.sidebar ul.nav > li.nav-media-services');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-voicemail').addClass('active');
        

         $("body").on('change', 'select.voicemail-prompt', function () {
         
            $('.sound-path').val($(this).find(":selected").attr('data-text'));

        });



    </script>


@endsection


@section('extra-css')

    <link type="text/css" rel="stylesheet" href="{{ URL::to('css/rSlider.css') }}" />

    <style type="text/css">
        
        ul.nav.nav-pills li.active a{
            background: #51BB8D !important;
            color: #fff !important;
            font-weight: bold;
        }

    </style>

@endsection