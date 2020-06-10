<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name', 'Cloud PBX') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="virtual PBX solution. " name="description" />
    <meta content="Abiodun Adeyinka, adeyinkab24@gmail.com" name="author" />

    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('images/logo.jpeg')}}">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/pace-master/themes/blue/pace-theme-flash.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/plugins/uniform/css/uniform.default.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/fontawesome/css/font-awesome.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/line-icons/simple-line-icons.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/offcanvasmenueffects/css/menu_cornerbox.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/waves/waves.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/switchery/switchery.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/3d-bold-navigation/css/style.css') }}" rel="stylesheet" type="text/css"/>


    <!-- Theme Styles -->
    <link href="{{ asset('operator_assets/css/modern.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('operator_assets/css/themes/white.css') }}" class="theme-color" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::to('css/uc.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.css" />

    <script src="{{ asset('assets/plugins/3d-bold-navigation/js/modernizr.js') }}"></script>
    <script src="{{ asset('assets/plugins/offcanvasmenueffects/js/snap.svg-min.js') }}"></script>


    @yield('extra-css')

</head>
<body>

     <div class="page-content">
         <div class="navbar" >
            @include('includes.operators.header')
        </div>

        <div class="page-inner">
            <div id="main-wrapper">

                @include('includes.operators.content')

            </div><!-- Main Wrapper -->
        </div><!-- Page Inner -->
    </div><!-- Page Content -->

    <!-- Scripts -->

    <script src="{{ asset('assets/plugins/jquery/jquery-2.1.3.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pace-master/pace.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-blockui/jquery.blockui.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/switchery/switchery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/uniform/jquery.uniform.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/offcanvasmenueffects/js/classie.js') }}"></script>
    <script src="{{ asset('assets/plugins/waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/3d-bold-navigation/js/main.js') }}"></script>
    <script src="{{ asset('operator_assets/js/modern.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.js"></script>


    @yield('extra-script')


</body>
</html>
