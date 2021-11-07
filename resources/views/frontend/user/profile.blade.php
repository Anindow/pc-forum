@extends('frontend.layouts.app')

@section('title', trans('trans.user_profile') . ' |')

@push('style')
    {{--    <style>--}}
    {{--        .nav-pills .nav-link.active, .nav-pills .show > .nav-link {--}}
    {{--            /*color: #fff;*/--}}
    {{--            background-color: #17a2b8;--}}
    {{--        }--}}
    {{--        .nav-pills .nav-link:not(.active):hover {--}}
    {{--            color: #17a2b8;--}}
    {{--        }--}}
    {{--    </style>--}}
@endpush

@section('content')
    <section class="py-5" id="profile">
        <div class="container">
            <div class="row">
                <div class="card shadow w-100">
                    <div class="media p-5">
                        <img
                            class="img-fluid rounded-circle mr-3"
                            src="{{getImage(imagePath()['profile']['path'] . '/' . auth()->user()->avatar, imagePath()['profile']['size'])}}"
                            style="width: 70px;height: 70px;"
                            alt="Avatar">
                        <div class="media-body">
                            <h3 class="mt-0">{{auth()->user()->full_name}}</h3>
                            <b>User Since:</b> {{auth()->user()->created_at->diffForHumans()}}
                        </div>
                    </div>
                    <hr>
                    <div class="card-body">
                        <h5 class="card-title px-5 pb-3">Personal information</h5>
                        <form method="POST" action="{{route('profile-update', auth()->user()->id)}}" enctype="multipart/form-data">
                            @method('PUT')
                            @csrf

                            <div class="media ">
                            <span class="pl-4" style="width: 150px; height: 70px; border-right: 1px solid #e0e0e0">
                            <img
                                class="img-fluid rounded-circle"
                                src="{{getImage(imagePath()['profile']['path'] . '/' . auth()->user()->avatar, imagePath()['profile']['size'])}}"
                                style="width: 70px; height: 70px; margin-bottom: -20px"
                                alt="Avatar">
{{--                                <input type="file" name="avatar" value="{{ Auth::check() ? 'default.jpg' : '' }}" id="avatarFile" style="display: none;">--}}
                                <input type="file" name="avatar" id="avatarFile" style="display: none;">
                                <span
                                    class="badge bg-success text-white text-uppercase m-2 p-1"
                                    onclick="document.getElementById('avatarFile').click();"
                                    style="cursor: pointer"
                                >
                                    Replace</span>

                            </span>

                                <div class="media-body pl-5">

                                    <div class=" col-8 ">
                                        <div class="form-row mr-5">
                                            <div class="form-group col-md-6">
                                                <label for="first_name">First Name</label>
                                                <input type="text" name="first_name"
                                                       value="{{auth()->user()->first_name}}" class="form-control"
                                                       id="first_name"
                                                       placeholder="First name">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="last_name">Last Name</label>
                                                <input type="text" name="last_name"
                                                       value="{{auth()->user()->last_name}}" class="form-control"
                                                       id="last_name"
                                                       placeholder="Last name">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <hr style="width: 100% !important;">
                            <h5 class="card-title px-5 pb-3"> Contact Information</h5>

                            <div class=" col-9">
                                <div class="form-group mx-5">
                                    <label for="address">Address</label>
                                    <textarea name="address" class="form-control" id="address"
                                              placeholder="Your address here"
                                              rows="3">{{auth()->user()->address}}</textarea>

                                </div>
                                <div class="form-group mx-5">
                                    <label for="phone">Phone Number</label>
                                    <input type="text" name="phone" value="{{auth()->user()->phone}}"
                                           class="form-control" id="phone"
                                           placeholder="Phone no">

                                </div>
                                <div class="form-group mx-5">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" value="{{auth()->user()->email}}"
                                           class="form-control" id="email"
                                           placeholder="email">

                                </div>
                                <div class="form-group mx-5">
                                    <label for="city">City</label>
                                    <input type="text" name="city" value="{{auth()->user()->city}}" class="form-control"
                                           id="city"
                                           placeholder="City">

                                </div>


                                <div class="form-group mx-5 mt-5">
                                    <button type="submit" class=" btn btn-sm btn-success">Update</button>
                                </div>

                            </div>
                        </form>
                        <hr class="my-5">
                        <h5 class="card-title px-5 pb-3">Change Password</h5>

                        <form method="POST" action="{{route('password-update', auth()->user()->id)}}">
                            @csrf
                            <div class=" col-9">
                                <div class="form-group ml-5">
                                    <label for="current_password">Current Password</label>
                                    <input type="password" name="current_password" class="form-control"
                                           id="current_password"
                                           placeholder="Current password">
                                    <span
                                        class="text-danger ">{{$errors->has('current_password') ? $errors->first('current_password') : ''}}</span>

                                </div>
                                <div class="form-group ml-5">
                                    <label for="password">New Password</label>
                                    <input type="password" name="password" class="form-control" id="password"
                                           placeholder="New password">
                                    <span
                                        class="text-danger ">{{$errors->has('password') ? $errors->first('password') : ''}}</span>

                                </div>
                                <div class="form-group ml-5">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control"
                                           id="password_confirmation"
                                           placeholder="Confirm password">

                                </div>


                                <div class="form-group ml-5 mt-5">
                                    <button type="submit" class=" btn btn-sm btn-success">Save Changes</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script>

        let user = @json($user);

        $(document).ready(function () {

            // //active tab on refresh
            // $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
            //     localStorage.setItem('activeTab', $(e.target).attr('href'));
            // });
            // var activeTab = localStorage.getItem('activeTab');
            // if(activeTab){
            //     $('#myTabs a[href="' + activeTab + '"]').tab('show');
            // }

            //active tab on refresh
            $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
                localStorage.setItem('activeTab', $(e.target).attr('href'));
            });
            var activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                $('#profileTab a[href="' + activeTab + '"]').tab('show');
            }

            //Initialize Select2 Elements
            // $('.select2').select2()


            //email validation check
            $.validator.addMethod("emailCheck",
                function (value, element) {
                    return /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value);
                },
            );
            //profile update form frontend validation
            $('#profileUpdateForm').validate({
                rules: {
                    first_name: {
                        required: true,
                    },
                    email: {
                        required: true,
                        emailCheck: true
                    },
                    avatar: {
                        accept: "image/jpeg,image/png",
                    },

                },
                messages: {
                    first_name: {
                        required: "Please enter a first name",
                    },
                    email: {
                        required: "Please enter a email",
                        emailCheck: "Please enter valid email"

                    },
                    avatar: {
                        accept: "Please upload an valid image(jpeg,jpg,png)",
                    },
                },

            });

            //image size validation
            $("#file").change(function () {
                $("#avatarError").html("");
                var file_size = $('#file')[0].files[0].size;

                if (file_size > 1024000) {
                    $("#avatarError").html("<p>File size is greater than 1MB</p>");
                    return false;
                }
                return true;
            });


        });
    </script>
@endpush
