<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name', 'Cloud PBX') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="virtual PBX solution. " name="description" />
    <meta content="Abiodun Adeyinka, adeyinkab24@gmail.com" name="author" />

    <!-- ================== BEGIN BASE CSS STYLE ================== -->
    <link rel="icon" href="{{asset('images/logo.jpeg')}}">

    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="{{ URL::to('assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::to('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::to('assets/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::to('assets/css/animate.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::to('assets/css/style.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::to('assets/css/style-responsive.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::to('assets/css/theme/default.css') }}" rel="stylesheet" id="theme" />
    <link href="{{ asset('css/uc.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <!-- ================== END BASE CSS STYLE ================== -->

    <!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
    <link href="{{ URL::to('assets/plugins/jquery-jvectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" />
    <link href="{{ URL::to('assets/plugins/bootstrap-datepicker/css/datepicker.css') }}" rel="stylesheet" />
    <link href="{{ URL::to('assets/plugins/bootstrap-datepicker/css/datepicker3.css') }}" rel="stylesheet" />
    <link href="{{ URL::to('assets/plugins/gritter/css/jquery.gritter.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/pricing-tables/css/style.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.css" />

    <!-- ================== END PAGE LEVEL STYLE ================== -->

    @yield('extra-css')

    <!-- ================== BEGIN BASE JS ================== -->
    <script src="{{ URL::to('assets/plugins/pace/pace.min.js') }}"></script>
    <!-- ================== END BASE JS ================== -->
</head>
<body>
    <!-- begin #page-loader -->
    <div id="page-loader" class="fade in"><span class="spinner"></span></div>
    <!-- end #page-loader -->

    <!-- begin #page-container -->
    <div id="page-container" class="fade  page-without-sidebar page-header-fixed">

        <div id="header" class="header navbar navbar-default navbar-fixed-top">
            <!-- begin container-fluid -->
            @include('includes.tenants.auth_header')
            <!-- end container-fluid -->
        </div>

        <div class="content" id="content">
            @yield('content')
       </div>

        <!-- begin scroll to top btn -->
        <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
        <!-- end scroll to top btn -->
    </div>
    <!-- end page container -->

    <!-- ================== BEGIN BASE JS ================== -->
    <!-- <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script> -->
    <script src="{{ URL::to('assets/plugins/jquery/jquery-1.9.1.min.js') }}"></script>
    <script src="{{ URL::to('assets/plugins/jquery/jquery-migrate-1.1.0.min.js') }}"></script>
    <script src="{{ URL::to('assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js') }}"></script>
    <script src="{{ URL::to('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pricing-tables/js/main.js') }}"></script>

    <script src="{{ URL::to('assets/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ URL::to('assets/plugins/jquery-cookie/jquery.cookie.js') }}"></script>
    <script src="{{ asset('js/jquery.mask.min.js') }}"></script>

    <!-- ================== END BASE JS ================== -->

    <!-- ================== BEGIN PAGE LEVEL JS ================== -->

    <script src="{{ URL::to('assets/js/dashboard.min.js') }}"></script>
    <script src="{{ URL::to('assets/js/apps.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.js"></script>

    <!-- ================== END PAGE LEVEL JS ================== -->

    @yield('extra-script')

    <script>
        $(document).ready(function() {
            App.init();
            // Dashboard.init();
        });
    </script>
    <!-- <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];
a.async=1;
a.src=g;
m.parentNode.insertBefore(a,m)
      })(window,document,'script','../../../../www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-53034621-1', 'auto');
      ga('send', 'pageview');
    </script> -->
</body>

</html>
