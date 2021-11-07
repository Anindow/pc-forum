@extends('frontend.layouts.app')

@section('title', trans('trans.register') . ' |')

@section('content')
    <section class="py-5" id="register">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="card shadow my-5">
                        <div class="card-header bg-success text-white">@lang('trans.register')</div>
                        <div class="card-body">
                            {{Form::open(['route' => 'register', 'method' => 'POST'])}}

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="firstName">First Name</label>
                                    <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"  id="firstName" placeholder="First name">
                                    @error('first_name') <span class="text-danger">{{$errors->first('first_name')}}</span> @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="lastName">Last Name</label>
                                    <input type="text" class="form-control" name="last_name" id="lastName" placeholder="Last name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="loginEmail">Email Address</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="loginEmail" placeholder="Enter email address">
                                @error('email') <span class="text-danger">{{$errors->first('email')}}</span> @enderror
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="registerPassword">Password</label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="registerPassword" placeholder="Enter password">
                                    @error('password') <span class="text-danger">{{$errors->first('password')}}</span> @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="confirmPassword">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" id="confirmPassword" placeholder="Confirm password">
                                </div>
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
