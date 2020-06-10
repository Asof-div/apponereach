@extends('layouts.operator_sidebar')

@section('title')

    CALL HISTORY

@endsection

@section('breadcrumb')

    <li ><a href="{{ route('operator.dashboard') }}"> Dashboard </a></li>
    <li class="active"> Call History </li>

@endsection

@section('content')


    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel ">
            <div class="panel-heading">
                <div class="panel-title pt-2">
                    <span class="h3"> &nbsp; Call History ({{ $calls->count() }})
                    </span>
                </div>
                <span class="pull-right mr-2">
                    <a href="{{ route('operator.call.history.export') }}" class="btn btn-default"> <i class="fa fa-cloud-download"></i> Export Call History </a>

                </span>

            </div>

            <div class="panel-body" style="min-height: 400px;">

                <hr style="background-color: #51BB8D; height: 3px;" />

                <div class="row">

                    @include('app.operator.call_history.partials.table')

                </div>

            </div>

        </div>
    </div>


@endsection


@section('extra-script')

    <script>

        $mn_list = $('.page-sidebar-inner ul.sidebar-nav > li.calls');
        $mn_list.addClass('active open');
        $mn_list.find('.sub-menu > .history-index').addClass('active');

       $('#detail_call_history_modal').on('show.bs.modal', function(evt){
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