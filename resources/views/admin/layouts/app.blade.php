<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App Url -->
    <meta name="app-url" content="{{ url('/') }}">
    <title> @yield('title') | {{settings('app_name')}}</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('assets/images/app/favicon.png')}}">
    <!-- Google Font: Source Sans Pro --><!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
{{--    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">--}}
    <!-- Sweetalert2 -->
    <link rel="stylesheet" href="{{asset('assets/admin/plugins/sweetalert2/sweetalert2.min.css')}}">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{asset('assets/admin/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- DataTable-->
    <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet"
          href="{{asset('assets/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{asset('assets/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- Bootstrap Switch -->
    <link rel="stylesheet" href="{{asset('assets/admin/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css')}}">
    <!-- Date picker -->
    <link rel="stylesheet"
          href="{{asset('assets/admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.standalone.min.css')}}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('assets/admin/plugins/select2/css/select2.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('assets/admin/css/adminlte.min.css')}}">
    <!-- OverlayScrollbars -->
{{--    <link rel="stylesheet" href="{{asset('assets/admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">--}}
<!-- Custom style -->
    <link rel="stylesheet" href="{{asset('assets/admin/css/custom.css')}}">

    <!-- Module CSS -->
    @stack('style')
</head>
{{--<body class="hold-transition sidebar-mini">--}}
<body class="hold-transition sidebar-mini accent-info">
<div id="app" class="wrapper">

    <!-- Navbar -->
@include('admin.includes.navbar')
<!-- /.navbar -->

    <!-- Main Sidebar Container -->
@include('admin.includes.sidebar')
<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @yield('content')
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    @include('admin.includes.footer')

</div>


<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="{{asset('assets/admin/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- SweetAlert2 -->
<script src="{{asset('assets/admin/plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
<!-- overlayScrollbar -->
{{--<script src="{{asset('assets/admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>--}}
<!-- DataTables -->
<script src="{{asset('assets/admin/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<!-- jquery-validation -->
<script src="{{asset('assets/admin/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/jquery-validation/additional-methods.min.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('assets/admin/plugins/select2/js/select2.full.min.js')}}"></script>
<!-- Bootstrap Datepicker -->
<script src="{{asset('assets/admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<!-- Bootstrap Switch -->
<script src="{{asset('assets/admin/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('assets/admin/js/adminlte.min.js')}}"></script>
<script src="{{asset('assets/admin/js/plugin.js')}}"></script>
<script>
    $(document).ready(function () {
        /* Sweetalert 2 toast  */
        const Toast = Swal.mixin({
            toast: true,
            position: "{{settings('toast_position')}}",
            showConfirmButton: false,
            timer: 3000
        });
        @if(Session::has('success'))
        Toast.fire({icon: 'success', title: `{{Session::get('success')}}`})
        @elseif(Session::has('warning'))
        Toast.fire({icon: 'warning', title: `{{Session::get('warning')}}`})
        @elseif(Session::has('error'))
        Toast.fire({icon: 'error', title: `{{Session::get('error')}}`})
        @elseif(Session::has('info'))
        Toast.fire({icon: 'info', title: `{{Session::get('info')}}`})
        @endif

    });

</script>
<!-- Module JS -->
@stack('script')
</body>
</html>
