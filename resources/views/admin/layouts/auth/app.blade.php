<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-url" content="{{ url('/') }}">
    <title> @yield('title') | {{settings('app_name')}}</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('assets/images/app/favicon.png')}}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{asset('assets/admin/plugins/sweetalert2/sweetalert2.min.css')}}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{asset('assets/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('assets/admin/css/adminlte.min.css')}}">
</head>
<body class="hold-transition login-page">
<div id="app">
    @yield('content')
</div>


<!-- jQuery -->
<script src="{{asset('assets/admin/plugins/jquery/jquery.min.js')}}"></script>
<!-- SweetAlert2 -->
<script src="{{asset('assets/admin/plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
<script>
    $(document).ready(function () {
        "use strict";
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
</body>
</html>
