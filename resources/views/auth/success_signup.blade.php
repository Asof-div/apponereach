@extends('layouts.auth')


@section('content')

    <!-- begin login -->
    <div class="login login-with-news-feed clearfix">

        
        <div id="header" class="header navbar navbar-default navbar-fixed-top clearfix">
            <div class="container-fluid">
                <!-- begin mobile sidebar expand / collapse button -->
                <div class="navbar-header">
                    <a href="{{ url('/') }}" class="navbar-nav">
                        <img class=" navbar-nav pull-left" src="{{asset('images/9MOBILE.jpeg')}}" alt="" style="width: 50px; height: 50px; display:inline;">      
                        <span class="navbar-brand"> Cloud PBX </span>
                    </a>
                    <button type="button" class="navbar-toggle" data-click="sidebar-toggled">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <!-- end mobile sidebar expand / collapse button -->
                
                <!-- begin header navigation right -->
                <ul class="nav navbar-nav navbar-right">
                    @guest('web')
                        <li>
                            <a href="{{ url('login') }}">Login</a>
                        </li>

                    @endguest
                </ul>
                <!-- end header navigation right -->
            </div>
        </div>
        <!-- end #header -->
        
        
        <!-- begin #content -->
        <div id="content" class="content-full-width page-content-full-height clearfix m-t-50 bg-silver" style="margin-top: 50px !important; height: calc(100% - 50px); padding: 50px;">

            <div class="well well-lg">

                <h3>Welcome To Cloud PBX ...... ENJOY </h3>
                
                <div class="p-t-10">
                    
                    <p class="f-s-18"> {{ $message }} </p>
                    
                    @if( !is_null($tenant) )
                    
                        <p class="f-s-15"> Company Name : {{ $tenant->name }}</p>
                        <p class="f-s-15"> Domain Name :  {{ $tenant->domain}} </p>
                        
                    @endif

                </div>

            </div>
            
            
        </div>

    </div>
    <!-- end login -->  

@endsection

@section('extra-script')

    @include('partials.fail')

@endsection

@section('extra-css')
    
    <style type="text/css">
        .login, .login-with-news-feed{
            /*background-color: #fff !important;*/
            height: 100% !important;
        }
        .has-error{
            background: #ffdedd !important;
            border-color: #ff5b57 !important;
        }
    </style>    

@endsection

