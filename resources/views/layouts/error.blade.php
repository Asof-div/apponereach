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
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('images/logo.png')}}">

    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="{{ URL::to('assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::to('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />

    <link href="{{ URL::to('css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::to('assets/css/animate.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::to('assets/css/style.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::to('assets/css/style-responsive.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::to('assets/css/theme/default.css') }}" rel="stylesheet" id="theme" />
    <!-- ================== END BASE CSS STYLE ================== -->
    

    @yield('extra-css')

    <!-- ================== BEGIN BASE JS ================== -->
    <script src="{{ URL::to('assets/plugins/pace/pace.min.js') }}"></script>
    <!-- ================== END BASE JS ================== -->
</head>
<body>
    
    <div id="page-loader" class="fade in"><span class="spinner"></span></div>
    
    <div id="page-container" class="fade">
        
        <div class="error">
           
            @yield('content')

        </div>
    
        <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
    
    </div>
    
    <!-- ================== BEGIN BASE JS ================== -->
    <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ URL::to('assets/plugins/jquery/jquery-migrate-1.1.0.min.js') }}"></script>
    <script src="{{ URL::to('assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js') }}"></script>
    <script src="{{ URL::to('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/custom_ajax/global_jscode.js') }}"></script>

    <script src="{{ URL::to('assets/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ URL::to('assets/plugins/jquery-cookie/jquery.cookie.js') }}"></script>
    <script src="{{ asset('js/jquery.mask.min.js') }}"></script>

    <!-- ================== END BASE JS ================== -->
    
    <!-- ================== BEGIN PAGE LEVEL JS ================== -->
    
    <script src="{{ URL::to('assets/js/apps.min.js') }}"></script>
    
    <!-- ================== END PAGE LEVEL JS ================== -->
    
    @yield('extra-script')

    <script>
        $(document).ready(function() {
            App.init();
            // Dashboard.init();
        });
    </script>

</body>

</html>
