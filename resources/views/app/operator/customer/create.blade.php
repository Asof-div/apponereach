@extends('layouts.operator_sidebar')

@section('title')
    
    CUSTOMER MANAGEMENT

@endsection

@section('breadcrumb')

    <li><a href="{{ route('operator.customer.index') }}"> Customer </a></li>
    <li class="active"> New Customer </li>

@endsection

@section('content')

    <div class="col-md-12" style="margin-top: 20px;">
        <div class="steps-form">
            <div class="steps-row setup-panel-2 d-flex justify-content-between">
                <div class="steps-step active">
                    <a href="#step-1" type="button" class="btn btn-amber btn-circle waves-effect ml-0" data-toggle="tooltip" data-placement="top" title="Customer Information"><i class="fa fa-folder-open-o" aria-hidden="true"></i></a>
                    <p class="f-s-15"> Customer Information </p>
                </div>
                <div class="steps-step disabled">
                    <a href="#step-2" type="button" class="btn btn-blue-grey btn-circle waves-effect" data-toggle="tooltip" data-placement="top" title="Get Pilot Line"><i class="fa fa-phone" aria-hidden="true"></i></a>
                    <p class="f-s-15"> Get Pilot Number </p>
                </div>
                <div class="steps-step disabled">
                    <a href="#step-3" type="button" class="btn btn-blue-grey btn-circle waves-effect" data-toggle="tooltip" data-placement="top" title="Check Out"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
                    <p class="f-s-15"> Confirm Check Out </p>
                </div>
            </div>
        </div>

    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 no-p">
        <div class="panel ">
            <div class="panel-heading"> 
                <div class="panel-title pt-2">
                    <span class="h3"> &nbsp; Add New Post Paid Customer  
                    </span>
                </div> 
                <span class="pull-right mr-2">
                    <a href="{{ route('operator.customer.index') }}" class="btn btn-success"> <i class="fa fa-list"></i> Customer List </a>
                </span>

            </div>

            <div class="panel-body" style="min-height: 400px;">

                <hr class="horizonal-line-thick" />

                <div class="col-md-12 col-sm-12 col-xs-12 ">

                    @include('app.operator.customer.partials.form')

                </div>

            </div>



        </div>
    </div>


@endsection


@section('extra-script')    

    <script>
       
        $mn_list = $('.page-sidebar-inner ul.sidebar-nav > li.customer');
        $mn_list.addClass('active open');
        $mn_list.find('.sub-menu > .customer-new').addClass('active');

        let root_url = "{{ request()->root() }}/tenant/";

        $('body').on('submit', '#customer_form',  function(event){
            event.preventDefault();
            $('#system_overlay').removeClass('hidden');

            let form = document.getElementById('customer_form');
            formData = new FormData(form);

            url = "{{ route('operator.customer.store') }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location = data.url;
                }, 3000);  

               
                form.reset();
                $('#system_overlay').addClass('hidden');

                
            }
            let failed = function(data){

                $('#system_overlay').addClass('hidden');
                printErrorMsg(data.error);
            }

            ajaxCall(url, formData, success, failed);  
            
            // $('#system_overlay').addClass('hidden');
        });

        $('body').on('input', '.domain-input', function(){

            let domain = $(this).val();
            domain = domain.replace(/[#\/\s*$@^&%()\\?\[\]\|\{\}\~\`]/g,'_');
            domain = domain.toLowerCase();

            $('.domain-url-text').text(root_url + domain);
            $('.domain-input').val(domain);

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