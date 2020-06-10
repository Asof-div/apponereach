@extends('layouts.tenant_sidebar')

@section('title')
    
    CONFERENCE MANAGEMENT

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.conference.audio.index', [$tenant->domain]) }}"> Conference </a></li>
    <li class="active"> Conference Details </li>

@endsection

@section('content')

   
    <div class="col-md-12 col-sm-12 col-xs-12 p-0">
        <div class="panel panel-default">
            <div class="panel-heading"> 
            
                <div class="panel-title"> 
                    <span class="h3"> Show Conference </span> 
                    <span class="pull-right m-r-10">
                        <a href="{{ route('tenant.conference.audio.create', [$tenant->domain]) }}" class="btn btn-outline-default"> <i class="fa fa-plus"></i>  </a>
                        <a href="{{ route('tenant.conference.audio.index', [$tenant->domain]) }}" class="btn btn-outline-default"> <i class="fa fa-list"></i>  </a>
                        <a href="javascript:;" class="btn btn-outline-default" data-toggle="modal" data-target=".edit-conference-modal"> <i class="fa fa-edit"></i>  </a>
                        <a href="javascript:;" class="btn bt btn-outline-default" data-toggle="modal" data-target=".delete-conference-modal"> <i class="fa fa-trash"></i>  </a>
                    </span>

                </div> 
                <hr class="horizonal-line-thick">
            </div>

            <div class="panel-body" style="min-height: 400px;">

                <div class="col-md-12 col-sm-12 col-xs-12" >

                    @include('app.tenant.conference.audio_conference.partials.details')

                </div>

            </div>
        </div>
    </div>

        

    @include('app.tenant.conference.audio_conference.modal.index')

@endsection


@section('extra-script')

    <script>
       
        $mn_list = $('.sidebar ul.nav > li.nav-conference');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-audio-conf').addClass('active');

        $('body').on('submit', '#edit_conference_form',  function(event){
            event.preventDefault();
            let form = document.getElementById('edit_conference_form');
            let formData = new FormData(form);

            url = "{{ route('tenant.conference.audio.update', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location.reload();
                }, 5000);  

                $('.modal').hide();
                
            }
            let failed = function(data){

            }

            ajaxCall(url, formData, success, failed);  

        });

        $('body').on('submit', '#delete_conference_form',  function(event){
            event.preventDefault();
            let form = document.getElementById('delete_conference_form');
            let formData = new FormData(form);

            url = "{{ route('tenant.conference.audio.delete', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location = "{{ route('tenant.conference.audio.index', [$tenant->domain]) }}";
                }, 5000);  

                $('.modal').hide();
                
            }
            let failed = function(data){

            }

            ajaxCall(url, formData, success, failed);  

        });




    
    </script>


@endsection

@section('extra-css')
        
    <style>
        a.btn{display: inline-block;}

    </style>

@endsection