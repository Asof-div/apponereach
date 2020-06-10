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
            
                <div class="panel-title"> 
                    <span class="h3"> Contacts <span class="text-primary"> ({{ $contacts->count() }}) </span> </span> 
                    <span class="pull-right m-r-10">
                        <a href="{{ route('tenant.crm.contact.create', [$tenant->domain]) }}" class="btn btn-lg btn-outline-default"> <i class="fa fa-plus-circle"></i> Add Contact </a>
                    </span>
                </div> 
                <hr class="horizonal-line-thick">
            </div>

            <div class="panel-body" style="min-height: 400px;">

                <div class="col-md-12 col-sm-12 col-xs-12" >

                    @include('app.tenant.crm.contact.partials.table')

                </div>

            </div>
        </div>
    </div>

        

    <div class="modal fade delete-contact-modal" tabindex="1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <form method="post" action="{{ route('tenant.crm.contact.delete', [$tenant->domain]) }}" id="delete_contact_form">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span> </button>
                        <h5 class="modal-title"> <span class="h4 text-primary"> DELETE CONTACT </span> </h5>
                    </div>

                    <div class="modal-body">
                     
                        {{ csrf_field() }}
                        <p class="f-s-15"> Are you sure you want to delete this ? </p>
                        <input type="hidden" name="contact_id" value="">
                         
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
        $mn_list.find('.sub-menu > .sub-menu-contact').addClass('active');

        $('.delete-contact-modal').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget);
            let contact_id = button.data('contact_id');

            var modal = $(this)
            modal.find('input[name=contact_id]').val(contact_id);
        });


        $('body').on('submit', '#delete_contact_form',  function(event){
            event.preventDefault();
            let form = document.getElementById('delete_contact_form');
            let formData = new FormData(form);

            url = "{{ route('tenant.crm.contact.delete', [$tenant->domain]) }}"; 

            let success = function(data){
                $('.delete-contact-modal').modal('hide');
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location = "{{ route('tenant.crm.contact.index', [$tenant->domain]) }}";
                }, 4000);  
                
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