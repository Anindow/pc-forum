<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App Url -->
    <meta name="app-url" content="{{ url('/') }}">
    <title>@yield('title') {{settings('app_name')}}</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('assets/images/app/favicon.png')}}">
    <!-- Sweetalert2 -->
    <link rel="stylesheet" href="{{asset('assets/admin/plugins/sweetalert2/sweetalert2.min.css')}}">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{asset('assets/frontend/css/bootstrap.css')}}">
    <!-- Fontawesome -->
    <link rel="stylesheet" href="{{asset('assets/frontend/css/all.min.css')}}">
    <!-- Style CSS -->
    <link rel="stylesheet" href="{{asset('assets/frontend/css/style.css')}}">
    <!-- Page level style -->
    @stack('style')
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{asset('assets/frontend/css/custom.css')}}">

    <style>
        #searchForm .form-control:focus,
        #searchForm .form-control:active {
            /*border-color: red !important;*/
            outline: 0;
            box-shadow: inset 0 0 0;
        }

        #searchForm {
            z-index: 2210;
            left: 0;
        }
    </style>
</head>
<body>

<!--Navbar Start-->
@include('frontend.includes.navbar')
<!--Navbar End-->

<!--Content Start-->
@yield('content')
<!--Content End-->


<!-- modal -->
{{--<!-- login modal -->--}}
{{--<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">--}}
{{--    <div class="modal-dialog">--}}
{{--        <div class="modal-content">--}}
{{--            <div class="modal-header">--}}
{{--                <h5 class="modal-title" id="loginModalLabel">Login</h5>--}}
{{--                <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                    <span aria-hidden="true">&times;</span>--}}
{{--                </button>--}}
{{--            </div>--}}
{{--            <div class="modal-body">--}}
{{--                <form>--}}
{{--                    <div class="form-group">--}}
{{--                        <label for="emailAddress">Email Address</label>--}}
{{--                        <input type="email" class="form-control" id="emailAddress" placeholder="Enter email address">--}}
{{--                    </div>--}}
{{--                    <div class="form-group">--}}
{{--                        <label for="password">Password</label>--}}
{{--                        <input type="password" class="form-control" id="password" placeholder="Enter password">--}}
{{--                    </div>--}}
{{--                    <div class="form-group form-check">--}}
{{--                        <input type="checkbox" class="form-check-input" id="exampleCheck1">--}}
{{--                        <label class="form-check-label" for="exampleCheck1">Check me out</label>--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--            <div class="modal-footer">--}}
{{--                <button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Close</button>--}}
{{--                <button type="submit" class="btn btn-outline-success btn-sm">Submit</button>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

<!-- sign up modal -->
{{--<div class="modal fade" id="signUpModal" tabindex="-1" aria-labelledby="signUpModalLabel" aria-hidden="true">--}}
{{--    <div class="modal-dialog">--}}
{{--        <div class="modal-content">--}}
{{--            <div class="modal-header">--}}
{{--                <h5 class="modal-title" id="signUpModalLabel">Sign Up</h5>--}}
{{--                <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                    <span aria-hidden="true">&times;</span>--}}
{{--                </button>--}}
{{--            </div>--}}
{{--            <div class="modal-body">--}}
{{--                <form>--}}
{{--                    <div class="form-row">--}}
{{--                        <div class="col-md-6 mb-3">--}}
{{--                            <label for="firstName">First Name</label>--}}
{{--                            <input type="text" class="form-control" id="firstName" placeholder="First name">--}}
{{--                        </div>--}}
{{--                        <div class="col-md-6 mb-3">--}}
{{--                            <label for="lastName">Last Name</label>--}}
{{--                            <input type="text" class="form-control" id="lastName" placeholder="Last name">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="form-group">--}}
{{--                        <label for="signUpEmailAddress">Email Address</label>--}}
{{--                        <input type="email" class="form-control" id="signUpEmailAddress" placeholder=" Enter email address">--}}
{{--                    </div>--}}
{{--                    <div class="form-row">--}}
{{--                        <div class="col-md-6 mb-3">--}}
{{--                            <label for="signUpPassword">Password</label>--}}
{{--                            <input type="password" class="form-control" id="signUpPassword" placeholder="Password">--}}
{{--                        </div>--}}
{{--                        <div class="col-md-6 mb-3">--}}
{{--                            <label for="confirmPassword">Confirm Password</label>--}}
{{--                            <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm password">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--            <div class="modal-footer">--}}
{{--                <button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Close</button>--}}
{{--                <button type="submit" class="btn btn-outline-success btn-sm">Submit</button>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}


<!-- Footer Start-->
@include('frontend.includes.footer')
<!-- Footer End-->



<script src="{{asset('assets/frontend/js/jquery-3.4.1.js')}}"></script>
<script src="{{asset('assets/frontend/js/bootstrap.js')}}"></script>
<!-- SweetAlert2 -->
<script src="{{asset('assets/admin/plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
<!-- Page level script -->
@stack('script')
<script>
    //get the current year for the copyright
    $('#year').text(new Date().getFullYear());



    $(document).ready(function () {

        // ==============================================================
        // Set global variables
        // ==============================================================
        let appUrl = document.head.querySelector('meta[name="app-url"]');
        if (appUrl) {
            window.app_url = appUrl.content;
        }


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

        //navbar search
        $('#searchForm').on('shown.bs.collapse', function () {
            // focus input on collapse
            $("#search").focus()
        })

        $('#searchForm').on('hidden.bs.collapse', function () {
            // focus input on collapse
            $("#search").blur()
        })

    });

</script>
</body>
</html>
