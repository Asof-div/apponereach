@extends('layouts.operator_sidebar')

@section('title')

    CALL RATE

@endsection

@section('breadcrumb')

    <li ><a href="{{ route('operator.dashboard') }}"> Dashboard </a></li>
    <li><a href="{{ route('operator.call.rate.index') }}"> Call Rates </a></li>
    <li class="active"> Edit Call Rate </li>

@endsection

@section('content')


    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel ">
            <div class="panel-heading">
                <div class="panel-title pt-2">
                    <span class="h3"> &nbsp; EdIt Call Rate
                    </span>
                </div>
                <span class="pull-right mr-2">
                    <a href="{{ route('operator.call.rate.index') }}" class="btn btn-success"> <i class="fa fa-list"></i> Call Rate List </a>
                    <a href="{{ route('operator.call.rate.template') }}" class="btn btn-default"> <i class="fa fa-cloud-download"></i> Download Template </a>
                </span>

            </div>

            <div class="panel-body" style="min-height: 400px;">

                <hr style="background-color: #51BB8D; height: 3px;" />

                <div class="col-md-12 col-sm-12 col-xs-12 p-t-15 p-b-15 bg-silver">

                    @include('app.operator.call_rate.partials.edit_form')

                </div>

            </div>



        </div>
    </div>


@endsection


@section('extra-script')

    <script>

        $mn_list = $('.page-sidebar-inner ul.sidebar-nav > li.calls');
        $mn_list.addClass('active open');
        $mn_list.find('.sub-menu > rate-index').addClass('active');

        $('body').on('submit', '#rate_form',  function(event){
            event.preventDefault();
            $('#system_overlay').removeClass('hidden');

            let form = document.getElementById('rate_form');
            formData = new FormData(form);

            url = "{{ route('operator.call.rate.update') }}";

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){
                    window.location = "{{ route('operator.call.rate.index') }}";
                    $('#system_overlay').addClass('hidden');
                }, 3000);

                form.reset();

            }
            let failed = function(data){

            }

            ajaxCall(url, formData, success, failed);

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