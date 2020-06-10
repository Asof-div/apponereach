@extends('layouts.tenant_sidebar')

@section('title')
    
    CONFERENCE MANAGEMENT

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.conference.audio.index', [$tenant->domain]) }}"> Audio Conference </a></li>
    <li class="active"> Create Conference </li>

@endsection

@section('content')

   
    <div class="col-md-12 col-sm-12 col-xs-12 p-0">
        <div class="panel panel-default">
            <div class="panel-heading"> 
                <div class="panel-title m-t-10"> 
                    <span class="h3"> Conference &nbsp; <span class="text-primary"> </span> </span> 
                    <span class="pull-right m-r-10">
                        <a href="{{ route('tenant.conference.audio.index', [$tenant->domain]) }}" class="btn btn-lg btn-outline-default"> <i class="fa fa-list"></i> All Conference </a>
                    </span>

                    <div class="panel-heading-btn m-r-10 m-t-10">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand" data-original-title="" title=""><i class="fa fa-expand"></i></a>
                    </div>
                </div> 
                <hr class="horizonal-line-thick clearfix">
            </div>

            <div class="panel-body" style="min-height: 400px;">
                @if(!Gate::check('private_meeting_room'))
                <div class="col-md-12">
                    <span class="h4 text-danger">Note !!! The maximum number of conference room for this package is 3</span>
                </div>
                @endif
                <div class="col-md-12 col-sm-12 col-xs-12 bg-silver" >

                    @include('app.tenant.conference.audio_conference.partials.form')

                </div>

            </div>
        </div>
    </div>

        



@endsection


@section('extra-script')
    <script type="text/javascript" src="{{ URL::to('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
    <script>
       
        $mn_list = $('.sidebar ul.nav > li.nav-conference');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-audio-conf').addClass('active');


       
        $('body').on('submit', '#conference_form',  function(event){
            event.preventDefault();
            let form = document.getElementById('conference_form');
            let formData = new FormData(form);

            url = "{{ route('tenant.conference.audio.store', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    url = "{{ route('tenant.conference.audio.index', [$tenant->domain]) }}"; 
                    window.location = url;
                }, 5000);  
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

        .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 30px;
        }

        .switch input {display:none;}

        .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
        }

        .slider:before {
        position: absolute;
        content: "";
        height: 22px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
        }

        input:checked + .slider {
        background-color: #2196F3;
        }

        input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
        border-radius: 34px;
        }

        .slider.round:before {
        border-radius: 50%;
        }


    </style>

@endsection