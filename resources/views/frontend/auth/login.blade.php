@extends('frontend.layouts.app')

@section('title', trans('trans.login') . ' |')

@section('content')
    <section class="py-5" id="login">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="card shadow my-5">
                        <div class="card-header bg-success text-white">@lang('trans.login')</div>
                        <div class="card-body">
                            {{Form::open(['route' => 'login', 'method' => 'POST'])}}
                            <div class="form-group">
                                <label for="loginEmail">Email Address</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="loginEmail" placeholder="Enter email address">
                                @error('email') <span class="text-danger">{{$errors->first('email')}}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="loginPassword">Password</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="loginPassword" placeholder="Enter password">
                                @error('password') <span class="text-danger">{{$errors->first('password')}}</span> @enderror

                            </div>

                            <div class="form-group form-check">
                                <input type="checkbox" name="remember" class="form-check-input" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label for="remember" class="form-check-label" >
                                    Remember Me
                                </label>
                            </div>
                            <button type="submit" class="btn btn-outline-success btn-sm float-right">Submit</button>
                            {{Form::close()}}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
