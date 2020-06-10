@extends('layouts.tenant_sidebar')

@section('title')
    
    CONTACT MANAGEMENT

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.crm.index', [$tenant->domain]) }}"> CRM </a></li>
    <li class="active"> CONTACT </li>

@endsection

@section('content')

   
    <div class="col-md-12 col-sm-12 col-xs-12 p-0">
        <div class="panel panel-default">
            <div class="panel-heading"> 
            
                <div class="panel-title p-5"> 
                    <span class="h3"> Total Contacts &nbsp; <span class="text-primary">  </span> </span> 
                    <span class="pull-right m-r-10">
                        <a href="{{ route('tenant.crm.contact.index', [$tenant->domain]) }}" class="btn btn-lg btn-outline-default"> <i class="fa fa-list"></i> List Contact </a>
                    </span>
                    <hr class="horizonal-line-thick">
                </div> 

            </div>

            <div class="panel-body" style="min-height: 400px;">

                <div class="col-md-12 col-sm-12 col-xs-12 bg-silver" >

                    @include('app.tenant.crm.contact.partials.form')

                </div>

            </div>
        </div>
    </div>

        



@endsection


@section('extra-script')
    <script type="text/javascript" src="{{ URL::to('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
    <script>
       
        $mn_list = $('.sidebar ul.nav > li.nav-crm');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-contact').addClass('active');


        $('body').on('submit', '#contact_form',  function(event){
            event.preventDefault();

            $("input[name='number'").val($("select[name='country_code'").val() + $("input[name='phone'").val());
            
            let form = document.getElementById('contact_form');
            let formData = new FormData(form);

            url = "{{ route('tenant.crm.contact.store', [$tenant->domain] ) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location = "{{ route('tenant.crm.contact.index', [$tenant->domain]) }}";
                }, 4000);  

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


    </style>

@endsection