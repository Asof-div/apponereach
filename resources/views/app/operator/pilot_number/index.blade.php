@extends('layouts.operator_sidebar')

@section('title')
    
    PILOT NUMBER

@endsection

@section('breadcrumb')

    <li ><a href="{{ route('operator.dashboard') }}"> Dashboard </a></li>
    <li class="active"> Pilot Numbers </li>

@endsection

@section('content')


    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel ">
            <div class="panel-heading"> 
                <div class="panel-title pt-2">
                    <span class="h3"> &nbsp; Pilot Number List ({{ $pilot_numbers->count() }}) 
                    </span>
                </div> 
                <span class="pull-right mr-2">
                    <a href="{{ route('operator.pilot_number.create') }}" class="btn btn-success"> <i class="fa fa-plus-circle"></i> New Pilot &nbsp; </a>
                    <a href="{{ route('operator.pilot_number.export') }}" class="btn btn-default"> <i class="fa fa-cloud-download"></i> Export Data </a>

                </span>

            </div>

            <div class="panel-body" style="min-height: 400px;">

                <hr style="background-color: #51BB8D; height: 3px;" />

                <div class="row">

                    @include('app.operator.pilot_number.partials.table')

                </div>

            </div>

        </div>
    </div>

    <div class="modal fade" id="delete_pilot_number_modal" tabindex="1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"> &times; </button>
                    <h5 class="modal-title">Delete Pilot Number </h5>
                </div>
                <div class="modal-body">
                    
                    <form id="pilot_form" name="13" class="form-horizontal form-material" method="POST" action="{{ route('operator.pilot_number.delete') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="pilot_id" value="">
                        <div class="form-group">
                            <div class="col-md-12 f-s-17">
                                Be sure you really want to delete this pilot. This action is re-reverseble .
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
       
        $mn_list = $('.page-sidebar-inner ul.sidebar-nav > li.pilot_number');
        $mn_list.addClass('active open');
        $mn_list.find('.sub-menu > .pilot_number-index').addClass('active');

       $('#delete_pilot_number_modal').on('show.bs.modal', function(evt){
            let button = $(evt.relatedTarget);
            $(this).find('[name="pilot_id"]').val(button.attr('data-id'));
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