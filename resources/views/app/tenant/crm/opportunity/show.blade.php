@extends('layouts.tenant_sidebar')

@section('title')
    
    OPPORTUNITY MANAGEMENT

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.crm.index', [$tenant->domain]) }}"> CRM </a></li>
    <li><a href="{{ route('tenant.crm.opportunity.index', [$tenant->domain]) }}"> CRM </a></li>
    <li class="active"> Opportunity </li>

@endsection

@section('content')

   
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading"> 
            
                <div class="panel-title"> 
                    <span class="h3"> Show Opportunity </span> 
                    <span class="pull-right m-r-10">
                        <a href="{{ route('tenant.crm.opportunity.create', [$tenant->domain]) }}" class="btn btn-lg btn-outline-default"> <i class="fa fa-plus-circle"></i> Add Opportunity </a>
                    </span>

                    <div class="panel-heading-btn m-r-10 m-t-10">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand" data-original-title="" title=""><i class="fa fa-expand"></i></a>
                    </div>
                </div> 

            </div>

            <div class="panel-body" style="min-height: 400px;">

                    <hr style="background-color: #51BB8D; height: 3px;">

                <br>


                <div class="col-md-12 col-sm-12 col-xs-12" >

                    @include('app.tenant.crm.opportunity.partials.details')

                </div>

            </div>
        </div>
    </div>

        

    @include('app.tenant.crm.opportunity.modal.index')

@endsection


@section('extra-script')

    <script>
       
        $mn_list = $('.sidebar ul.nav > li.nav-crm');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-opportunity').addClass('active');

        $('body').on('submit', '#add_opportunity_line_form',  function(event){
            event.preventDefault();
            let form = document.getElementById('add_opportunity_line_form');
            let formData = new FormData(form);

            url = "{{ route('tenant.crm.opportunity.opportunity-line.store', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 

                }, 5000);  

                $.gritter.add({
                    title: 'Opportunity Created',
                    text: data.success,
                    sticky: false,
                    time: '8000'
                });
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





        #pipe{
            width: 100%;
            padding: 0px;
            height: 25px;
            min-height: 30px;
            //background: #e0e4e7;
            margin: 20px 0;
            border-radius: 5px;
            position: relative;
            display: table;
            overflow: visible;
        }
        #pipe>ul{
            border-radius: 5px;
            width: 100%;
            margin: 0px;
            display: table;
            border-collapse: collapse;
            table-layout: fixed;
            height: 25px;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            -ms-box-sizing: border-box;
            -o-box-sizing: border-box;
            box-sizing: border-box;
            overflow: hidden;
            list-style: none;
        }
        #pipe>ul>li{

            position: relative;
            background: #e0e4e7;
            display: table-cell;
            line-height: 1.3em;
            vertical-align: middle;
            color: #fff;
            text-align: center;
            cursor: pointer;
            width: 50%;
        }

        #pipe>ul>li.activestage{
            background-color: #72C66F;
            background: #43c35e;
        }




        #pipe>ul>li>div{
            position: relative;
        }
        #pipe>ul>li>div>span.stagename{
            font-size: 12px;
            display: inline-block;
            position: relative;
            left: 4px;
        }
        #pipe>ul>li>div>span.stagearrow::after{
            width: 22px;
            height: 30px;
            position: absolute;
            right: -6px;
            top: 5px;
            border-top: 3px solid #fff;
            border-right: 5px solid #fff;
            -webkit-transform: scaleX(0.3) rotate(45deg);
            -moz-transform: scaleX(0.3) rotate(45deg);
            -ms-transform: scaleX(0.3) rotate(45deg);
            -o-transform: scaleX(0.3) rotate(45deg);
            transform: scaleX(0.3) rotate(45deg);
            content: " ";
            cursor: pointer;
            background: #e0e4e7;
        }

        #pipe>ul>li>div>span.stagearrow{
            width: 6px;
            height: 30px;
            position: absolute;
            top: -6px;
            left: 0px;
            overflow: hidden;
        }

        #pipe>ul>li.activestage + li div .stagearrow::after, #pipe>ul>li.activestage + li div .stagearrow::after{

            border-top: 3px solid #fff;
            border-right: 5px solid #fff;
            background: #43c35e;

        }

    </style>

@endsection