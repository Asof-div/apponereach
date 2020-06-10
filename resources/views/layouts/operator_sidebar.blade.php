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

    <style type="text/css">
        .overlay {
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            position: fixed;
            z-index: 10000;
            display: block;
        }

        .overlay__inner {
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            position: absolute;
        }

        .overlay__content {
            left: 50%;
            position: absolute;
            top: 50%;
            transform: translate(-50%, -50%);
        }

        .spinner {
            width: 75px;
            height: 75px;
            display: inline-block;
            border-width: 2px;
            border-color: rgba(255, 255, 255, 0.05);
            border-top-color: #fff;
            animation: spin 1s infinite linear;
            border-radius: 100%;
            border-style: solid;
        }


        @keyframes spin {
          100% {
            transform: rotate(360deg);
          }
        }


    </style>
    @yield('extra-css')

</head>
<body class="page-header-fixed">
    <div class="overlay hidden" id="system_overlay">
        <div class="overlay__inner">
            <div class="overlay__content"><span class="spinner"></span></div>
        </div>
    </div>

    <div class="page-content content-wrap">

        <div class="navbar" >
            @include('includes.operators.header')
        </div>

        <div class="page-sidebar sidebar">
            @include('includes.operators.sidebar')
        </div>

        <div class="page-inner">

            <div class="page-title">
                @include('includes.operators.title')
            </div>

            <div id="main-wrapper" class="clearfix">

                @include('includes.operators.content')

            </div>

        {{--     <div class="page-footer">
                @include('includes.operators.footer')
            </div>
         --}}

        </div>

    </div>


    <div class="cd-overlay"></div>



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
    {{-- <script src="{{ asset('assets/plugins/offcanvasmenueffects/js/main.js') }}"></script> --}}
    <script src="{{ asset('assets/plugins/waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/3d-bold-navigation/js/main.js') }}"></script>
    <script src="{{ asset('operator_assets/js/modern.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.js"></script>
    <script src="{{asset('js/Chart.bundle.min.js')}}"></script>

    <script src="{{ asset('js/custom_ajax/global_jscode01.js') }}"></script>
    <script src="{{ URL::to('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.datepicker').datepicker({
                format: 'yyyy-m-d',
            });
        });

    </script>

    <!-- ================== END BASE JS ================== -->


    @yield('extra-script')


</body>
</html>
