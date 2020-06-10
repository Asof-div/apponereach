@extends('layouts.operator_sidebar')

@section('title')
    
    CUSTOMER MANAGEMENT

@endsection

@section('breadcrumb')

    <li><a href="{{ route('operator.customer.index') }}"> Customer </a></li>
    <li><a href="{{ route('operator.customer.show', [$customer->id]) }}"> Details </a></li>
    <li class="active"> Edit Customer </li>

@endsection

@section('content')

    <div class="col-md-12 col-sm-12 col-xs-12 no-p">
        <div class="panel ">
            <div class="panel-heading"> 
                <div class="panel-title pt-2">
                    <span class="h3"> &nbsp; Edit Customer  
                    </span>
                </div> 
                <span class="pull-right mr-2">
                    <a href="{{ route('operator.customer.index') }}" class="btn btn-success"> <i class="fa fa-list"></i> Customer List </a>
                </span>

            </div>

            <div class="panel-body" style="min-height: 400px;">

                <hr class="horizonal-line-thick" />

                <div class="col-md-12 col-sm-12 col-xs-12 ">

                    @include('app.operator.customer.partials.edit_form')

                </div>

            </div>



        </div>
    </div>


@endsection


@section('extra-script')    

    <script>
       
        $mn_list = $('.page-sidebar-inner ul.sidebar-nav > li.customer');
        $mn_list.addClass('active open');
        $mn_list.find('.sub-menu > .customer-index').addClass('active');

        let root_url = "{{ request()->root() }}/tenant/";

        $('body').on('submit', '#customer_form',  function(event){
            event.preventDefault();
            $('#system_overlay').removeClass('hidden');

            let form = document.getElementById('customer_form');
            formData = new FormData(form);

            url = "{{ route('operator.customer.update') }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location = data.url;
                }, 4000);  

               
                form.reset();
                $('#system_overlay').addClass('hidden');

                
            }
            let failed = function(data){

                $('#system_overlay').addClass('hidden');
            }

            ajaxCall(url, formData, success, failed);  
            
            // $('#system_overlay').addClass('hidden');
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
        .p-l-15{
            padding-left: 15px !important;
        }

        .display-no{
            display: none;

        }
    </style>

@endsection