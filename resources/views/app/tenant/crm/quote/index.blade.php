@extends('layouts.tenant_sidebar')

@section('title')
    
    QUOTE

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.crm.index', [$tenant->domain]) }}"> CRM </a></li>
    <li class="active"> Quotes </li>

@endsection

@section('content')

   
    <div class="col-md-12 col-sm-12 col-xs-12 p-0">
        <div class="panel panel-default">
            
            <div class="panel-body" style="min-height: 400px;">

                <div class="col-md-12 col-sm-12 col-xs-12 m-b-10" >
                    <a class="btn btn-primary" href="{{ route('tenant.crm.quote.create', [$tenant->domain]) }}"> <i class="fa fa-plus"> New Quote</i></a>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12 p-0" >

                    @include('app.tenant.crm.quote.partials.table')

                </div>

            </div>
        </div>
    </div>

    <div class="modal fade delete-quote-modal" tabindex="1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <form method="post" action="{{ route('tenant.crm.quote.delete', [$tenant->domain]) }}" id="delete_quote_form">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span> </button>
                        <h5 class="modal-title"> <span class="h4 text-primary"> DELETE QUOTE </span> </h5>
                    </div>

                    <div class="modal-body">
                     
                        {{ csrf_field() }}
                        <p class="f-s-15"> Are you sure you want to delete this ? </p>
                        <input type="hidden" name="quote_id" value="">
                         
                    </div>

                    <div class="modal-footer">
                        <div class="form-inline">
                            <div class="form-group m-r-10">
                                <button type="button" class="btn btn-warning" data-dismiss="modal"> NO </button>
                                <button type="submit" class="btn btn-primary"> YES </button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>            
        </div>
    </div>

@endsection


@section('extra-script')

    <script>
       
        $mn_list = $('.sidebar ul.nav > li.nav-crm');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-quote').addClass('active');


        $('body').on('click', '.change-status',  function(event){
            event.preventDefault();
            let _token = "{{ csrf_token() }}"
            let quote_id = $(this).attr('data-quote_id');
            let status = $(this).attr('data-status');
            let formData = new FormData();
            formData.append('_token', _token);
            formData.append('quote_id', quote_id);
            formData.append('status', status);

            url = "{{ route('tenant.crm.quote.status', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location.reload();
                }, 5000);  
                
            }
            let failed = function(data){

            }

            ajaxCall(url, formData, success, failed);  

        });

        $('body').on('click', '.convert-quote',  function(event){
            event.preventDefault();
            let _token = "{{ csrf_token() }}"
            let quote_id = $(this).attr('data-quote_id');
            let formData = new FormData();
            formData.append('_token', _token);
            formData.append('quote_id', quote_id);

            url = "{{ route('tenant.crm.invoice.convert', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location = data.url;
                }, 5000);  
                
            }
            let failed = function(data){

            }

            ajaxCall(url, formData, success, failed);  

        });

        $('.delete-quote-modal').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget);
            let quote_id = button.data('quote_id');

            var modal = $(this)
            modal.find('input[name=quote_id]').val(quote_id);
        });


        $('body').on('submit', '#delete_quote_form',  function(event){
            event.preventDefault();
            let form = document.getElementById('delete_quote_form');
            let formData = new FormData(form);

            url = "{{ route('tenant.crm.quote.delete', [$tenant->domain]) }}"; 

            let success = function(data){
                $('.delete-quote-modal').modal('hide');
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location = "{{ route('tenant.crm.quote.index', [$tenant->domain]) }}";
                }, 5000);  
                
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