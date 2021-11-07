@extends('admin.layouts.auth.app')

@section('title')
    @lang('trans.login')
@endsection

@section('content')
    <div class="login-box">
        <div class="login-logo font-weight-bold">
            <a href="{{route('admin.login')}}"><b>{{settings('app_name')}}</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">@lang('trans.login')</p>

                {{Form::open(['route'=>'login', 'method'=> 'POST'])}}
                <div class="input-group ">
                    <input type="text" value="admin@mail.com" name="email" class="form-control" placeholder="Email or mobile phone number">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>

                </div>

                <span class="text-danger d-inline-block">{{$errors->has('email') ? $errors->first('email') : ''}}</span>
                <div class="input-group">
                    <input type="password" value="123456" name="password" class="form-control" placeholder="Password">

                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <span class="text-danger ">{{$errors->has('password') ? $errors->first('password') : ''}}</span>
                <div class="row mt-3">
                    <div class="col-7 col-sm-8">
                        <div class="icheck-info">
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember">
                                Remember Me
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-5 col-sm-4">
                        <button type="submit" class="btn btn-info btn-block">@lang('trans.sign_in')</button>
                    </div>
                    <!-- /.col -->
                </div>
                {{Form::close()}}
            <!-- /.social-auth-links -->

                {{--<p class="mb-1">--}}
                {{--<a href="#">I forgot my password</a>--}}
                {{--</p>--}}

                <hr>
                <p class="mb-1">
                <span>
                    <b>Email:</b> admin@mail.com
                </span>
                </p>
                <p class="mb-1">
                <span>
                    <b>password:</b> 123456
                </span>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
@endsection


