@extends('layouts.tenant_sidebar')

@section('title')
    
    OPPORTUNITY MANAGEMENT

@endsection

@section('breadcrumb')

    <li><a href="{{ url($tenant->domain.'/dashboard') }}"> Dashboard </a></li>
    <li><a href="{{ url($tenant->domain.'/crm') }}"> CRM </a></li>
    <li class="active"> Opportunity </li>

@endsection

@section('content')

   
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading"> 
            
                <div class="panel-title"> 
                    <span class="h3"> Total Opportunities &nbsp; <span class="text-primary"> {{ $opportunities->count() }} </span> </span> 
                    <span class="pull-right m-r-10">
                        <a href="{{ url($tenant->domain.'/crm/opportunity-create') }}" class="btn btn-lg btn-outline-default"> <i class="fa fa-plus-circle"></i> Add Opportunity </a>
                    </span>

                    <div class="panel-heading-btn m-r-10 m-t-10">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand" data-original-title="" title=""><i class="fa fa-expand"></i></a>
                    </div>
                </div> 

            </div>

            <div class="panel-body" style="min-height: 400px;">

                    <hr style="background-color: #51BB8D; height: 3px;">

                <br>


                <div class="col-md-12 col-sm-12 col-xs-12" id="">

                    @include('app.tenant.crm.opportunity.partials.table')

                </div>

            </div>
        </div>
    </div>

        



@endsection


@section('extra-script')

    <script>
       
        $mn_list = $('.sidebar ul.nav > li.nav-crm');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-opportunity').addClass('active');



    
    </script>


@endsection

@section('extra-css')
        
    <style>


    </style>

@endsection