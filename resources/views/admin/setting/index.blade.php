@extends('admin.layouts.app')
@section('title') System Setting @endsection
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">System Setting</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">@lang('trans.dashboard')</a></li>
                        <li class="breadcrumb-item active">System Setting</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-warning shadow">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-exclamation-triangle"></i> These system settings are
                                very useful to enable/disable certain features.</h3>
                        </div>
                        <div class="card-body">
                            {{Form::open(['route' => 'settings.store', 'method' => 'POST', 'class' => 'form-horizontal'])}}
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group row no-gutters">
                                        <label for="app_name" class="col-sm-3 col-form-label">App Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="app_name" class="form-control" id="app_name"
                                                   value="{{settings('app_name')}}" placeholder="App name">
                                            <span
                                                class="text-danger">{{$errors->has('app_name') ? $errors->first('app_name') : ''}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group row no-gutters">
                                        <label for="app_url" class="col-sm-3 col-form-label">App Url/Website</label>

                                        <div class="col-sm-9">
                                            <input type="text" name="app_url" class="form-control" id="app_url"
                                                   value="{{settings('app_url')}}" placeholder="App url/website">
                                            <span
                                                class="text-danger">{{$errors->has('app_url') ? $errors->first('app_url') : ''}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group row no-gutters">
                                        <label for="timezone" class="col-sm-3 col-form-label">Timezone</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" disabled name="timezone" id="timezone">
                                                @foreach($timezones as $timezone)
                                                    <option
                                                        {{settings('timezone') == $timezone ? 'selected' : ''}} value="{{$timezone}}">{{$timezone}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group row no-gutters">
                                        <label for="time_format" class="col-sm-3 col-form-label">Time Format</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="time_format" id="time_format">
                                                <option
                                                    {{settings('time_format') == 'H:mm' ? 'selected' : ''}} value="H:mm">
                                                    H:mm
                                                </option>
                                                <option
                                                    {{settings('time_format') == 'h:mm a' ? 'selected' : ''}} value="h:mm a">
                                                    h:mm a
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group  row no-gutters">
                                        <label for="per_page" class="col-sm-3 col-form-label">Datatable Per Page</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="per_page" id="per_page">
                                                <option {{settings('per_page') == 10 ? 'selected' : ''}} value="10">10
                                                    per
                                                    page
                                                </option>
                                                <option {{settings('per_page') == 25 ? 'selected' : ''}} value="25">25
                                                    per
                                                    page
                                                </option>
                                                <option {{settings('per_page') == 50 ? 'selected' : ''}} value="50">50
                                                    per
                                                    page
                                                </option>
                                                <option {{settings('per_page') == 100 ? 'selected' : ''}} value="100">
                                                    100
                                                    per page
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group row no-gutters">
                                        <label for="date_format" class="col-sm-3 col-form-label">Date Format</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="date_format" id="date_format">
                                                <option
                                                    {{settings('date_format') == 'DD-MM-YYYY' ? 'selected' : ''}} value="DD-MM-YYYY">
                                                    DD-MM-YYYY
                                                </option>
                                                <option
                                                    {{settings('date_format') == 'MM-DD-YYYY' ? 'selected' : ''}} value="MM-DD-YYYY">
                                                    MM-DD-YYYY
                                                </option>
                                                <option
                                                    {{settings('date_format') == 'DD-MMM-YYYY' ? 'selected' : ''}} value="DD-MMM-YYYY">
                                                    DD-MMM-YYYY
                                                </option>
                                                <option
                                                    {{settings('date_format') == 'MMM-DD-YYYY' ? 'selected' : ''}} value="MMM-DD-YYYY">
                                                    MMM-DD-YYYY
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group row no-gutters">
                                        <label for="toast_position" class="col-sm-3 col-form-label">Toast
                                            Position</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="toast_position" id="toast_position">
                                                <option
                                                    {{settings('toast_position') == 'top-start' ? 'selected' : ''}} value="top-start">
                                                    Top Start
                                                </option>
                                                <option
                                                    {{settings('toast_position') == 'top-end' ? 'selected' : ''}} value="top-end">
                                                    Top End
                                                </option>
                                                <option
                                                    {{settings('toast_position') == 'bottom-start' ? 'selected' : ''}} value="bottom-start">
                                                    Bottom Start
                                                </option>
                                                <option
                                                    {{settings('toast_position') == 'bottom-end' ? 'selected' : ''}} value="bottom-end">
                                                    Bottom End
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group row no-gutters">
                                        <label for="copyright_text" class="col-sm-3 col-form-label">Copyright Text</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="copyright_text" class="form-control" id="copyright_text"
                                                   value="{{settings('copyright_text')}}" placeholder="Copyright text">

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit"
                                        class="btn btn-sm btn-info float-right">@lang('trans.update')</button>
                            </div>
                            {{Form::close()}}

                            </form>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
@endsection
