@extends('admin.layouts.app')

@section('title') @lang('trans.user_profile')  @endsection
@push('style')
    <style>
        .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
            /*color: #fff;*/
            background-color: #17a2b8;
        }
        .nav-pills .nav-link:not(.active):hover {
            color: #17a2b8;
        }
    </style>
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">@lang('trans.profile')</h1>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">@lang('trans.dashboard')</a></li>
                        <li class="breadcrumb-item active">@lang('trans.profile')</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <div class="card shadow">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle profile-avatar-style"
                                     src="{{getImage(imagePath()['profile']['path'] . '/' . auth()->user()->avatar, imagePath()['profile']['size'])}}"
                                     alt="User profile picture">
                            </div>
                            <h3 class="profile-username text-center">{{$user->full_name}}</h3>
                            <p class="text-muted text-center">{{trans('trans.member_since')}} {{showDiffForHuman($user->created_at)}}</p>
                        </div>
                    </div>
                    <div class="card shadow">
                        <div class="card-header">
                            <h3 class="card-title">@lang('trans.about_me')</h3>
                        </div>
                        <div class="card-body">
                            <strong><i class="far fa-envelope mr-1"></i> Email</strong>
                            <p class="text-muted">
                                <span class="tag tag-danger">{{$user->email}}</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="card shadow">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills" id="profileTab">
                                <li class="nav-item"><a class="nav-link active" href="#profile" data-toggle="tab">@lang('trans.profile')</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">@lang('trans.settings')</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="profile">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row no-gutters">
                                                <span class="col-sm-2 font-weight-bold">First
                                                    Name</span>
                                                <div class="col-sm-10">
                                                    <span>{{$user->first_name}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row no-gutters">
                                                <span class="col-sm-2 font-weight-bold">Last Name</span>
                                                <div class="col-sm-10">
                                                    <span>{{$user->last_name}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row no-gutters">
                                                <span class="col-sm-2 font-weight-bold">Email</span>
                                                <div class="col-sm-10">
                                                    <span>{{$user->email}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row no-gutters">
                                                <span class="col-sm-2 font-weight-bold">Avatar</span>
                                                <div class="col-sm-10">
                                                    <div class="">
                                                        <img src="{{getImage(imagePath()['profile']['path'] . '/' . auth()->user()->avatar, imagePath()['profile']['size'])}}" class="rounded-circle img-fluid profile-avatar-replace-style">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="settings">

                                    {{Form::open([ 'route'=> ['users.profile-update', $user->id], 'method' => 'PUT','enctype' => 'multipart/form-data', 'autocomplete' => 'off', 'id' => 'profileUpdateForm'])}}
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row no-gutters">
                                                <label for="first_name" class="col-sm-2 col-form-label">First
                                                    Name</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="first_name"
                                                           class="form-control @error('first_name') is-invalid @enderror"
                                                           id="first_name" value="{{$user->first_name}}">
                                                    <span class="text-danger ">
                                                        {{$errors->has('first_name') ? $errors->first('first_name') : ''}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row no-gutters">
                                                <label for="last_name" class="col-sm-2 col-form-label">Last Name</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="last_name"
                                                           class="form-control @error('last_name') is-invalid @enderror"
                                                           id="last_name" value="{{$user->last_name}}">
                                                    <span class="text-danger ">
                                                        {{$errors->has('last_name') ? $errors->first('last_name') : ''}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row no-gutters">
                                                <label for="email" class="col-sm-2 col-form-label">Email</label>
                                                <div class="col-sm-10">
                                                    <input type="email" name="email"
                                                           class="form-control @error('email') is-invalid @enderror"
                                                           id="email" value="{{$user->email}}">
                                                    <span class="text-danger ">
                                                        {{$errors->has('email') ? $errors->first('email') : ''}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row no-gutters">
                                                <label for="address1" class="col-sm-2 col-form-label">Avatar</label>
                                                <div class="col-sm-10">
                                                    <div class="">
                                                        <img src="{{getImage(imagePath()['profile']['path'] . '/' . auth()->user()->avatar, imagePath()['profile']['size'])}}" class="rounded-circle img-fluid profile-avatar-replace-style" alt="User image">
                                                        <input type="file"
                                                               class="form-control d-none avatar"
                                                               name="avatar" id="file" accept="image/png,image/jpeg">

                                                        <span class="badge badge-info text-white profile-avatar-replace"
                                                              onclick="document.getElementById('file').click();">
                                                            Replace
                                                        </span>
                                                        <small class="text-danger d-block error-right-msg" id="avatarError">
                                                            {{$errors->has('avatar') ? $errors->first('avatar') : ''}}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-info float-right">@lang('trans.update')</button>
                                    {{Form::close()}}

                                    <hr class="mt-5 border border-dark">

                                    {{--  Password Change--}}
                                    {{Form::open([ 'route'=> ['users.password-update', $user->id], 'method' => 'POST', 'autocomplete' => 'off', 'id' => 'passwordChangeForm'])}}
                                    <h3 class="mb-5"><b>Change Password</b></h3>
                                    <div class="container mt-3">
                                        <div class="form-group row no-gutters">
                                            <label for="current_password" class="col-sm-2 col-form-label">Current
                                                Password</label>
                                            <div class="col-sm-10">
                                                <input type="password" name="current_password"
                                                       class="form-control @error('current_password') is-invalid @enderror"
                                                       placeholder="Current Password"
                                                       id="current_password">
                                                <span class="text-danger ">
                                                    {{$errors->has('current_password') ? $errors->first('current_password') : ''}}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group row no-gutters">
                                            <label for="new_password" class="col-sm-2 col-form-label">New
                                                Password</label>
                                            <div class="col-sm-10">
                                                <input type="password" name="password"
                                                       class="form-control @error('password') is-invalid @enderror"
                                                       placeholder="New Password"
                                                       id="password">
                                                <span class="text-danger">
                                                    {{$errors->has('password') ? $errors->first('password') : ''}}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group row no-gutters">
                                            <label for="confirmPassword" class="col-sm-2 col-form-label">Confirm
                                                Password</label>
                                            <div class="col-sm-10">
                                                <input type="password" name="password_confirmation"
                                                       class="form-control @error('password_confirmation') is-invalid @enderror"
                                                       placeholder="Confirm Password"
                                                       id="confirmPassword">
                                                <span class="text-danger ">
                                                    {{$errors->has('password_confirmation') ? $errors->first('password_confirmation') : ''}}
                                                </span>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-info float-right">@lang('trans.save_changes')</button>
                                        {{Form::close()}}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
            $("#file").change(function(){
                $("#avatarError").html("");
                var file_size = $('#file')[0].files[0].size;

                if(file_size > 1024000) {
                    $("#avatarError").html("<p>File size is greater than 1MB</p>");
                    return false;
                }
                return true;
            });



        });
    </script>
@endpush
