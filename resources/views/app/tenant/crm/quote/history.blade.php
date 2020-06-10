@extends('layouts.tenant_sidebar')

@section('title')
    
    Quote 

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.crm.index', [$tenant->domain]) }}"> CRM </a></li>
    <li><a href="{{ route('tenant.crm.quote.index', [$tenant->domain]) }}"> Quote </a></li>
    <li class="active"> Quote History </li>

@endsection

@section('content')

   
    <div class="col-md-12 col-sm-12 col-xs-12 p-0">
        <div class="panel panel-default">

            <div class="panel-body" style="min-height: 400px;">
                <div class="col-md-12 col-sm-12 col-xs-12" >
                    <span class="pull-left">
                        <span class="h4"> Quote No: {{ $quote->quote_no }}</span>
                    </span>
                    <span class="pull-right">
                        <a class="btn" href="{{ route('tenant.crm.quote.index', [$tenant->domain]) }}"> <i class="fa fa-list"></i> Quote List </a>
                        <a class="btn" href="{{ route('tenant.crm.quote.create', [$tenant->domain]) }}"> <i class="fa fa-plus"></i> New Quote </a>

                        <a class="btn" href="{{ route('tenant.crm.quote.edit', [$tenant->domain, $quote->id]) }}"> <i class="fa fa-edit"></i> Edit </a>
                    </span>
                </div>
                <hr class="horizonal-line-thick">
                <div class="col-md-12 col-sm-12 col-xs-12" >

                    @include('app.tenant.crm.quote.partials.details')

                </div>

            </div>
        </div>
    </div>

        



@endsection


@section('extra-script')

    <script>
       
        $mn_list = $('.sidebar ul.nav > li.nav-crm');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-quote').addClass('active');

        $('body').on('change', '.history-list', function (event) {

            
            var content = $(this).val();

            $('#preview_content').attr('data', content);


        });

    
    </script>


@endsection

@section('extra-css')
        
    <style>



    </style>

@endsection