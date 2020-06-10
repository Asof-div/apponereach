@extends('layouts.operator_sidebar')

@section('title')

    CALL RATE

@endsection

@section('breadcrumb')

    <li ><a href="{{ route('operator.dashboard') }}"> Dashboard </a></li>
    <li class="active"> Call Rate </li>

@endsection

@section('content')


    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel ">
            <div class="panel-heading">
                <div class="panel-title pt-2">
                    <span class="h3"> &nbsp; Call Rate ({{ $rates->count() }})
                    </span>
                </div>
                <span class="pull-right mr-2">
                    <a href="{{ route('operator.call.rate.create') }}" class="btn btn-success"> <i class="fa fa-plus-circle"></i> New Rate &nbsp; </a>
                    <a href="{{ route('operator.call.rate.export') }}" class="btn btn-default"> <i class="fa fa-cloud-download"></i> Export Call Rate </a>

                </span>

            </div>

            <div class="panel-body" style="min-height: 400px;">

                <hr style="background-color: #51BB8D; height: 3px;" />

                <div class="row">

                    @include('app.operator.call_rate.partials.table')

                </div>

            </div>

        </div>
    </div>

    <div class="modal fade" id="delete_call_rate_modal" tabindex="1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"> &times; </button>
                    <h5 class="modal-title">Delete Call Rate </h5>
                </div>
                <div class="modal-body">

                    <form id="pilot_form" name="13" class="form-horizontal form-material" method="POST" action="{{ route('operator.call.rate.delete') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="id" value="">
                        <div class="form-group">
                            <div class="col-md-12 f-s-17">
                                Be sure you really want to delete this rate. This action is re-reverseble .
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

        $mn_list = $('.page-sidebar-inner ul.sidebar-nav > li.calls');
        $mn_list.addClass('active open');
        $mn_list.find('.sub-menu > .rate-index').addClass('active');

       $('#delete_call_rate_modal').on('show.bs.modal', function(evt){
            let button = $(evt.relatedTarget);
            $(this).find('[name="id"]').val(button.attr('data-id'));
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