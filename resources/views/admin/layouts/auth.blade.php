<!DOCTYPE html>
<html dir="ltr" lang="{{ app()->getLocale() }}" class="light-style customizer-hide">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="description" content="">
    <meta name="author" content="">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <!-- Favicon icon -->
    <link rel="icon" type="image/x-icon" sizes="16x16" href="{{ asset('images/admin/favicon/favicon.ico') }}" />
    <title>Administrator :: @if ($title) {{ $title }} @else {{ getAppName() }} @endif</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" />
    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('css/admin/vendor/fonts/boxicons.css') }}" />
    <!-- Font Awesome -->
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" /> --}}

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('css/admin/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('css/admin/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('css/admin/vendor/css/pages/page-auth.css') }}" />
    <!-- Helpers -->
    <script src="{{ asset('js/admin/vendor/js/helpers.js') }}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('js/admin/config.js') }}"></script>

    <!-- Toastr css -->
    <link href="{{ asset('css/admin/vendor/libs/toastr/toastr.min.css') }}" rel="stylesheet">

    <!-- Development css -->
    <link href="{{ asset('css/admin/development.css') }}" rel="stylesheet">
</head>

<body>
    @include('admin.includes.notification')

    <!-- Preloader -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- All Required js -->
    <!-- ============================================================== -->
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('js/admin/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('js/admin/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('js/admin/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('js/admin/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('js/admin/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- jQuery -->
    <script src="{{ asset('js/admin/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/admin/development.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('js/admin/main.js') }}"></script>
    
    <!-- Preloader -->
    <script type="text/javascript">
    $(".preloader").fadeOut();
    </script>

    <!-- Toastr js & rendering -->
    <script src="{{ asset('js/admin/vendor/libs/toastr/toastr.min.js') }}"></script>
    <script>
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    </script>
</body>

</html>
