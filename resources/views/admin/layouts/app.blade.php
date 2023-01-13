<!DOCTYPE html>
<html dir="ltr" lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Administrator :: @if($title){{$title}} @else {{ getAppName() }} @endif</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.png') }}"/>

    @if (Route::currentRouteName() == 'admin.dashboard')
    <link href="{{ asset('css/admin/plugins/c3/c3.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin/plugins/chartist/dist/chartist.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin/plugins/jvector/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet">
    @endif
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/admin/dist/style.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    {{-- <link href="{{ asset('css/admin/plugins/fontawesome/css/font-awesome.min.css') }}" rel="stylesheet"> --}}
    @if (strpos(Route::currentRouteName(), '.gallery') !== false)
    <!-- Ekko Lightbox -->
    <link rel="stylesheet" href="{{ asset('css/admin/plugins/ekko-lightbox/ekko-lightbox.css') }}">
    @endif
    <!-- Tooltip -->
    <link href="{{ asset('css/admin/plugins/tooltip/microtip.min.css') }}" rel="stylesheet">
    @if (strpos(Route::currentRouteName(), '.list') !== false)
    <!-- DataTables -->
    <link href="{{ asset('css/admin/plugins/datatables.net-bs4/dataTables.bootstrap4.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin/plugins/datatables-responsive/responsive.bootstrap4.min.css') }}" rel="stylesheet">
    @endif
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('css/admin/plugins/select2/select2.min.css') }}">
    <!-- Sweetalert -->
    <link href="{{ asset('css/admin/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
    <!-- Fancybox -->
    <link href="{{ asset('css/admin/plugins/fancybox/jquery.fancybox.min.css') }}" rel="stylesheet">
    <!-- jQuery -->
    <script src="{{ asset('js/admin/dist/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('js/admin/jquery.validate.min.js') }}"></script>
    <!-- Toastr css -->
    <link href="{{ asset('css/admin/plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    <!-- Development css -->
    <link href="{{ asset('css/admin/development.css') }}" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
    
    @include('admin.includes.notification')

    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
        <!-- ============================================================== -->
        <!-- Topbar header -->
        <!-- ============================================================== -->
        @include('admin.includes.header')
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->

        <!-- ============================================================== -->
        <!-- Left Sidebar -->
        <!-- ============================================================== -->
        @include('admin.includes.side_menu')
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->

        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            @include('admin.includes.breadcrumb')
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                @yield('content')
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            @include('admin.includes.footer')
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('js/admin/plugins/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('js/admin/plugins/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- ============================================================== -->
    <!-- This page plugin js -->
    <!-- ============================================================== -->
    <script type="text/javascript">
        $(".preloader").fadeOut();
    </script>
    <!-- apps -->
    <script src="{{ asset('js/admin/dist/app-style-switcher.js') }}"></script>
    <script src="{{ asset('js/admin/dist/feather.min.js') }}"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{ asset('js/admin/plugins/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
    <script src="{{ asset('js/admin/plugins/sparkline/sparkline.js') }}"></script>
    <!--Wave Effects -->
    <!-- themejs -->
    <!--Menu sidebar -->
    <script src="{{ asset('js/admin/dist/sidebarmenu.js') }}"></script>
    <!--Custom JavaScript -->
    <script src="{{ asset('js/admin/dist/custom.min.js') }}"></script>

    @if (Route::currentRouteName() == 'admin.dashboard')
    <!--Custom JavaScript -->
    <script src="{{ asset('js/admin/plugins/c3/d3.min.js') }}"></script>
    <script src="{{ asset('js/admin/plugins/c3/c3.min.js') }}"></script>
    <script src="{{ asset('js/admin/plugins/chartist/dist/chartist.min.js') }}"></script>
    <script src="{{ asset('js/admin/plugins/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js') }}"></script>
    <script src="{{ asset('js/admin/plugins/jvector/jquery-jvectormap-2.0.2.min.js') }}"></script>
    <script src="{{ asset('js/admin/plugins/jvector/jquery-jvectormap-world-mill-en.js') }}"></script>
    {{-- <script src="{{ asset('js/admin/dist/pages/dashboards/dashboard1.min.js') }}"></script> --}}
    <!-- Chart JS -->
    {{-- <script src="{{ asset('js/admin/dist/pages/chartjs/chartjs.init.js') }}"></script>
    <script src="{{ asset('js/admin/plugins/chart.js/dist/Chart.min.js') }}"></script> --}}
    <!--Morris JavaScript -->
    {{-- <script src="{{ asset('js/admin/plugins/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('js/admin/plugins/morris.js/morris.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('js/admin/dist/pages/morris/morris-data.js') }}"></script> --}}
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.1.0/chart.min.js"></script>
    @endif

    <!-- Select2 -->
    <script src="{{ asset('js/admin/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/admin/plugins/moment/moment.min.js') }}"></script>
    <!-- date-range-picker -->
    <link rel="stylesheet" href="{{asset('css/admin/plugins/bootstrap-daterangepicker/daterangepicker.css')}}">
    <script src="{{ asset('js/admin/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <!-- material date time picker -->
    <link rel="stylesheet" href="{{asset('css/admin/plugins/material-datetimepicker/bootstrap-material-datetimepicker.css')}}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="{{ asset('js/admin/plugins/material-datetimepicker/bootstrap-material-datetimepicker.js') }}"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $('#end_date_time').bootstrapMaterialDatePicker({
            weekStart: 0,
            format: 'YYYY-MM-DD HH:mm',
        });
        $('#start_date_time').bootstrapMaterialDatePicker({
            weekStart: 0,
            format: 'YYYY-MM-DD HH:mm',
            shortTime : true
        }).on('change', function(e, date) {
            $('#end_date_time').bootstrapMaterialDatePicker('setMinDate', date);
        });
    });
    </script>

    @if (strpos(Route::currentRouteName(), '.gallery') !== false)
    <!-- Ekko Lightbox -->
    <script src="{{ asset('js/admin/plugins/ekko-lightbox/ekko-lightbox.min.js') }}"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox({
                alwaysShowClose: true
            });
        });
    });
    </script>
    @endif

    @if (strpos(Route::currentRouteName(), '.list') !== false)        
    <!-- DataTables -->
    <script src="{{ asset('js/admin/plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/admin/dist/pages/datatable/datatable-basic.init.js') }}"></script>
    <script src="{{ asset('js/admin/plugins/datatables-responsive/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/admin/plugins/datatables-responsive/responsive.bootstrap4.min.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            $('#responsive-table').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
    @endif
    
    @if (strpos(Route::currentRouteName(), '.add') !== false || strpos(Route::currentRouteName(), '.edit') !== false || strpos(Route::currentRouteName(), '.profile') !== false)
    <script src="{{ asset('js/admin/plugins/ckeditor/ckeditor.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            try {
                if ($('#description').length) {
                    CKEDITOR.replace('description', {
                        filebrowserUploadUrl: "{{route('admin.ckeditor-upload', ['_token' => csrf_token() ])}}",
                        filebrowserUploadMethod: 'form',
                        allowedContent: true
                    });
                }
                if ($('#description_popup_top').length) {
                    CKEDITOR.replace('description_popup_top', {
                        filebrowserUploadUrl: "{{route('admin.ckeditor-upload', ['_token' => csrf_token() ])}}",
                        filebrowserUploadMethod: 'form',
                        allowedContent: true
                    });
                }
                if ($('#description_popup_bottom').length) {
                    CKEDITOR.replace('description_popup_bottom', {
                        filebrowserUploadUrl: "{{route('admin.ckeditor-upload', ['_token' => csrf_token() ])}}",
                        filebrowserUploadMethod: 'form',
                        allowedContent: true
                    });
                }
                if ($('#description2').length) {
                    CKEDITOR.replace('description2', {
                        filebrowserUploadUrl: "{{route('admin.ckeditor-upload', ['_token' => csrf_token() ])}}",
                        filebrowserUploadMethod: 'form',
                        allowedContent: true
                    });
                }
                if ($('#other_description').length) {
                    CKEDITOR.replace('other_description', {
                        filebrowserUploadUrl: "{{route('admin.ckeditor-upload', ['_token' => csrf_token() ])}}",
                        filebrowserUploadMethod: 'form',
                        allowedContent: true
                    });
                }
            } catch {

            }
        });
    </script>
    <!-- Cropper -->
    <link href="{{ asset('css/admin/plugins/croppie/croppie.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/admin/plugins/croppie/croppie.js') }}"></script>
    @endif
    <!-- Sweetalert -->
    <script src="{{ asset('js/admin/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <!-- Fancybox -->
    <script src="{{ asset('js/admin/plugins/fancybox/jquery.fancybox.min.js') }}"></script>
    <!-- Toastr js & rendering -->
    <script src="{{ asset('js/admin/plugins/toastr/toastr.min.js') }}"></script>
    @toastr_render
    <script src="{{ asset('js/admin/development.js') }}"></script>
    <script type="text/javascript">    
    $(function () {
        $('.select2').select2();
    });
    </script>

    <!-- Selectpicker -->
    <link rel="stylesheet" href="{{asset('css/admin/plugins/selectpicker/bootstrap-select.css')}}">
	<script src="{{asset('js/admin/plugins/selectpicker/bootstrap-select.js')}}"></script>
    <script type="text/javascript">    
    $(function () {
        $('.selectpicker').selectpicker({
            // maxOptions:2,
            // actionsBox: false,
        });
    });    
    </script>

    @if (strpos(Route::currentRouteName(), '.sort') !== false || strpos(Route::currentRouteName(), '.details') !== false)
    <link href="{{ asset('css/admin/plugins/nestable/nestable.css') }}" rel="stylesheet">
    <script src="{{ asset('js/admin/plugins/nestable/jquery.nestable.js') }}"></script>
    @endif

    @stack('scripts')

</body>

</html>