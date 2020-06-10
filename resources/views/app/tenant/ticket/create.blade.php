@extends('layouts.tenant_sidebar')

@section('title')
    
    TICKET MANAGEMENT

@endsection

@section('breadcrumb')

    <li ><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.ticket.index', [$tenant->domain]) }}"> Tickets </a></li>
    <li class="active"> Create </li>

@endsection

@section('content')


    <div class="col-md-12 col-sm-12 col-xs-12 p-0">
        <div class="panel ">
            <div class="panel-heading"> 
                <div class="panel-title pt-2">
                    <span class="h3"> &nbsp; Tickets 
                    </span>
                    <span class="pull-right mr-2">
                        <a href="{{ route('tenant.ticket.index', [$tenant->domain]) }}" class="btn btn-success"> <i class="fa fa-list"></i> Tickets &nbsp; <span class="text-primary"> </span> </a>
                    </span>
                </div>
                <hr class="horizonal-line-thick" />
            </div>

            <div class="panel-body" style="min-height: 400px;">

                <div class="col-md-12 col-sm-12 col-xs-12 p-t-15 p-b-15 bg-silver">

                    @include('app.tenant.ticket.partials.form')

                </div>

            </div>



        </div>
    </div>


@endsection


@section('extra-script')    

    <script>
       
        $mn_list = $('.sidebar ul.nav > li.nav-ticket');
        $mn_list.addClass('active');



        $('body').on('submit', '#ticket_form',  function(event){
            event.preventDefault();
            $('#system_overlay').removeClass('hidden');

            formData = new FormData(document.getElementById('ticket_form'));

            url = "{{ route('tenant.ticket.store', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                $('#system_overlay').addClass('hidden');
                document.getElementById('ticket_form').reset();
                setTimeout(function(){ 
                    window.location = data.url;
                }, 6000);  

                
            }
            let failed = function(data){

                $('#system_overlay').addClass('hidden');
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