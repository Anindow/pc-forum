@extends('admin.layouts.app')
@section('title')
    User
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row no-gutters mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Manage User</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">@lang('trans.dashboard')</a></li>
                        <li class="breadcrumb-item active">User</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="row no-gutters">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h3 class="card-title float-left">All Users
                        </h3>
                        @can('create-user')
                            <button type="button" data-toggle="modal" data-target="#createUserModal"
                                    class="btn btn-info float-right btn-sm"><i
                                    class="fa fa-plus"></i> Add New
                            </button>
                        @endcan
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="usersDataTable"
                               class="table table-bordered table-striped dataTable dtr-inline text-center">
                            <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th style="width: 20%">Name</th>
                                <th style="width: 15%">Phone</th>
                                <th style="width: 20%">Email</th>
                                <th style="width: 15%">Roles</th>
                                <th style="width: 5%">Status</th>
                                <th style="width: 20%">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($i = 1)
                            @foreach($users as $user)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$user->full_name}}</td>
                                    <td>{{$user->phone}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>
                                        @foreach($user->roles as $key => $role)
                                            {{--                                            {{$key++ ? ', ': ''}} --}}
                                            <span class="badge badge-success">{{$role->name}}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)"
                                           @can('status-change-user')
                                           onclick="statusChange('{{$user->id}}')"
                                           @endcan
                                           data-href="{{route('users.status-update', $user->id)}}"
                                           data-toggle="tooltip"
                                           title="@lang('trans.change_status')"
                                           id="userStatus-{{$user->id}}"
                                        >
                                            <span
                                                class="badge {{$user->status == 1 ? 'badge-success' : 'badge-danger'}}">
                                            {{$user->status == 1 ? 'Active' : 'Inactive' }}
                                        </span>
                                        </a>
                                    </td>
                                    <td>
                                        @can('show-user')
                                            <button type="button" class="btn btn-sm btn-primary"
                                                    data-toggle="tooltip"
                                                    title="@lang('trans.view')"
                                                    onclick="openShowUserModal({{$user->id}})"><i
                                                    class="fa fa-search-plus"></i>
                                            </button>
                                        @endcan
                                        @can('update-user')
                                            <button onclick="openEditUserModal({{$user->id}})" type="button"
                                                    data-toggle="tooltip"
                                                    title="@lang('trans.edit')"
                                                    class="btn btn-sm btn-success"><i class="fa fa-edit"></i></button>
                                        @endcan
                                        @can('delete-user')
                                            {{Form::open(['route'=>['users.destroy', $user->id], 'method'=>'DELETE', 'class' => 'd-inline', 'id' => "deleteForm-$user->id", ])}}
                                            <button type="submit"
                                                    onclick="deleteBtn('{{$user->id}}')"
                                                    data-toggle="tooltip"
                                                    title="@lang('trans.delete')"
                                                    class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                            {{Form::close()}}
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="showUserModal" tabindex="-1" aria-labelledby="showUserModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="showUserModalLabel">User Show</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-borderless">
                            <tbody>
                            <tr>
                                <th>First Name:</th>
                                <td id="showUserFirstName"></td>
                            </tr>
                            <tr>
                                <th>Last Name:</th>
                                <td id="showUserLastName"></td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td id="showUserPhone"></td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td id="showUserEmail"></td>
                            </tr>
                            <tr>
                                <th>Division:</th>
                                <td id="showUserDivision"></td>
                            </tr>
                            <tr>
                                <th>District:</th>
                                <td id="showUserDistrict"></td>
                            </tr>
                            <tr>
                                <th>Upazila:</th>
                                <td id="showUserUpazila"></td>
                            </tr>
                            <tr>
                                <th>Address:</th>
                                <td id="showUserAddress"></td>
                            </tr>
                            <tr>
                                <th>Avatar:</th>
                                <td><img src="" id="showUserAvatar" alt="User image"
                                         class="profile-user-img img-fluid img-circle"></td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td id="showUserStatus"></td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div id="loadingDiv">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createUserModalLabel">Create User</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        {{Form::open(['route' => 'users.store', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal', 'id' => 'userCreateForm',])}}
                        <div class="modal-body">
                            <!-- text input -->
                            <div class="form-group row no-gutters">
                                <label for="first_name" class="col-sm-3 col-form-label mandatory">First Name</label>
                                <div class="col-sm-9">
                                    <input type="text" name="first_name"
                                           class="form-control @error('first_name') is-invalid @enderror"
                                           value="{{old('first_name')}}" id="first_name" placeholder="Enter first name">
                                    <span
                                        class="text-danger">{{$errors->has('first_name') ? $errors->first('first_name') : ''}}</span>
                                </div>
                            </div>
                            <div class="form-group row no-gutters">
                                <label for="last_name" class="col-sm-3 col-form-label">Last Name</label>
                                <div class="col-sm-9">
                                    <input type="text" name="last_name"
                                           class="form-control @error('last_name') is-invalid @enderror"
                                           value="{{old('last_name')}}" id="last_name" placeholder="Enter last name">
                                    <span
                                        class="text-danger">{{$errors->has('last_name') ? $errors->first('last_name') : ''}}</span>
                                </div>
                            </div>
                            <div class="form-group row no-gutters">
                                <label for="phone" class="col-sm-3 col-form-label">Phone</label>
                                <div class="col-sm-9">
                                    <input type="number" name="phone"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           value="{{old('phone')}}" id="phone" placeholder="Enter phone number">
                                    <span
                                        class="text-danger">{{$errors->has('phone') ? $errors->first('phone') : ''}}</span>
                                </div>
                            </div>
                            <div class="form-group row no-gutters">
                                <label for="email" class="col-sm-3 col-form-label mandatory">Email</label>
                                <div class="col-sm-9">
                                    <input type="email" name="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{old('email')}}" id="email" placeholder="Enter email address">
                                    <span
                                        class="text-danger">{{$errors->has('email') ? $errors->first('email') : ''}}</span>
                                </div>
                            </div>
{{--                            <div class="form-group row no-gutters">--}}
{{--                                <label for="division" class="col-sm-3 col-form-label">Division</label>--}}
{{--                                <div class="col-sm-9">--}}
{{--                                    <select class="form-control select2 select2-info" name="division_id" id="division"--}}
{{--                                            style="width: 100%;" data-dropdown-css-class="select2-info">--}}
{{--                                        <option value="">Select Division</option>--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="form-group row no-gutters">--}}
{{--                                <label for="district" class="col-sm-3 col-form-label">District</label>--}}
{{--                                <div class="col-sm-9">--}}
{{--                                    <select--}}
{{--                                        class="form-control select2 select2-info"--}}
{{--                                        data-dropdown-css-class="select2-info"--}}
{{--                                        name="district_id" id="district" style="width: 100%;">--}}
{{--                                        <option value="">Select District</option>--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="form-group row no-gutters">--}}
{{--                                <label for="upazila" class="col-sm-3 col-form-label">Upazila</label>--}}
{{--                                <div class="col-sm-9">--}}
{{--                                    <select--}}
{{--                                        class="form-control select2 select2-info"--}}
{{--                                        data-dropdown-css-class="select2-info"--}}
{{--                                        name="upazila_id" id="upazila" style="width: 100%;">--}}
{{--                                        <option value="">Select Upazila</option>--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="form-group row no-gutters">
                                <label for="address" class="col-sm-3 col-form-label">Address</label>
                                <div class="col-sm-9">
                                    <textarea name="address" id="address" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="form-group row no-gutters">
                                <label for="password" class="col-sm-3 col-form-label mandatory">Password</label>
                                <div class="col-sm-9">
                                    <input type="password" name="password"
                                           class="form-control @error('password') is-invalid @enderror" id="password"
                                           placeholder="Enter password">
                                    <span
                                        class="text-danger">{{$errors->has('password') ? $errors->first('password') : ''}}</span>
                                </div>
                            </div>
                            <div class="form-group row no-gutters">
                                <label for="password_confirmation" class="col-sm-3 col-form-label mandatory">Confirm
                                    Password</label>
                                <div class="col-sm-9">
                                    <input type="password" name="password_confirmation"
                                           class="form-control @error('password_confirmation') is-invalid @enderror"
                                           id="password_confirmation" placeholder="Enter confirm password">
                                    <span
                                        class="text-danger">{{$errors->has('password_confirmation') ? $errors->first('password_confirmation') : ''}}</span>
                                </div>
                            </div>
                            <div class="form-group row no-gutters">
                                <label for="profile-avatar" class="col-sm-3 col-form-label" id="inputGroupFileAddon05">Avatar</label>
                                <div class="col-sm-9">
                                    <div class="custom-file">
                                        <input type="file" name="avatar" class="custom-file-input"
                                               accept="image/jpeg, image/png" id="profile-avatar"
                                               aria-describedby="inputGroupFileAddon05">
                                        <label class="custom-file-label" for="profile-avatar">Choose avatar</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row no-gutters">
                                <label class="col-sm-3 col-form-label mandatory">Role</label>
                                <div class="col-sm-9 mt-2">
                                    @foreach($roles as $id=>$role)
                                        <div class="icheck-success d-inline-block mr-5">
                                            <input type="checkbox" name="role_id[]" value="{{$id}}" id="create-{{$id}}">
                                            <label for="create-{{$id}}">{{$role}}</label>
                                        </div>
                                    @endforeach
                                    <p class="text-danger">{{$errors->has('role_id') ? $errors->first('role_id') : ''}}</p>
                                </div>
                            </div>
                            <div class="form-group row no-gutters">
                                <label for="status" class="col-sm-3 col-form-label">Status</label>
                                <div class="col-sm-9">
                                    <input type="checkbox" id="status" name="status" data-bootstrap-switch
                                           data-off-color="danger" data-on-color="success">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-sm btn-info">Create</button>
                            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                        {{Form::close()}}
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div id="editLoadingDiv">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        {{Form::open(['id' => 'userEditForm', 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal'])}}
                        <div class="modal-body">
                            <div class="form-group row no-gutters">
                                <label for="editUserFirstName" class="col-sm-3 col-form-label mandatory">First
                                    Name</label>
                                <div class="col-sm-9">
                                    <input type="text" name="first_name"
                                           class="form-control @error('first_name') is-invalid @enderror"
                                           id="editUserFirstName" placeholder="Enter first name">
                                    <span
                                        class="text-danger">{{$errors->has('first_name') ? $errors->first('first_name') : ''}}</span>
                                </div>
                            </div>
                            <div class="form-group row no-gutters">
                                <label for="editUserLastName" class="col-sm-3 col-form-label">Last Name</label>
                                <div class="col-sm-9">
                                    <input type="text" name="last_name"
                                           class="form-control @error('last_name') is-invalid @enderror"
                                           id="editUserLastName" placeholder="Enter last name">
                                    <span
                                        class="text-danger">{{$errors->has('last_name') ? $errors->first('last_name') : ''}}</span>
                                </div>
                            </div>
                            <div class="form-group row no-gutters">
                                <label for="editUserPhone" class="col-sm-3 col-form-label">Phone</label>
                                <div class="col-sm-9">
                                    <input type="number" name="phone"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           id="editUserPhone" placeholder="Enter phone number">
                                    <span
                                        class="text-danger">{{$errors->has('phone') ? $errors->first('phone') : ''}}</span>
                                </div>
                            </div>
                            <div class="form-group row no-gutters">
                                <label for="editUserEmail" class="col-sm-3 col-form-label mandatory">Email</label>
                                <div class="col-sm-9">
                                    <input type="email" name="email"
                                           class="form-control @error('email') is-invalid @enderror" id="editUserEmail"
                                           placeholder="Enter email address">
                                    <span
                                        class="text-danger">{{$errors->has('email') ? $errors->first('email') : ''}}</span>
                                </div>
                            </div>
{{--                            <div class="form-group row no-gutters">--}}
{{--                                <label for="editUserDivision" class="col-sm-3 col-form-label">Division</label>--}}
{{--                                <div class="col-sm-9">--}}
{{--                                    <select class="form-control select2 select2-info" name="division_id"--}}
{{--                                            id="editUserDivision" style="width: 100%;"--}}
{{--                                            data-dropdown-css-class="select2-info">--}}
{{--                                        <option value="">Select Division</option>--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="form-group row no-gutters">--}}
{{--                                <label for="editUserDistrict" class="col-sm-3 col-form-label">District</label>--}}
{{--                                <div class="col-sm-9">--}}
{{--                                    <select--}}
{{--                                        class="form-control select2 select2-info"--}}
{{--                                        data-dropdown-css-class="select2-info"--}}
{{--                                        name="district_id" id="editUserDistrict" style="width: 100%;">--}}
{{--                                        <option value="">Select District</option>--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="form-group row no-gutters">--}}
{{--                                <label for="editUserUpazila" class="col-sm-3 col-form-label">Upazila</label>--}}
{{--                                <div class="col-sm-9">--}}
{{--                                    <select--}}
{{--                                        class="form-control select2 select2-info"--}}
{{--                                        data-dropdown-css-class="select2-info"--}}
{{--                                        name="upazila_id" id="editUserUpazila" style="width: 100%;">--}}
{{--                                        <option value="">Select Upazila</option>--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="form-group row no-gutters">
                                <label for="editUserAddress" class="col-sm-3 col-form-label">Address</label>
                                <div class="col-sm-9">
                                    <textarea name="address" id="editUserAddress" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="form-group row no-gutters">
                                <label for="editUserAvatar" class="col-sm-3 col-form-label">Avatar</label>
                                <div class="col-sm-9">
                                    <div class="custom-file">
                                        <input type="file" name="avatar" class="custom-file-input"
                                               accept="image/jpeg, image/png"
                                               aria-describedby="editUserAvatar">
                                        <label class="custom-file-label" for="editUserAvatar">Choose avatar</label>
                                    </div>
                                    <img src="" id="editUserAvatar" class="img-fluid mt-2"
                                         style="height: 80px; width: 80px;" alt="">
                                </div>
                            </div>
                            <div class="form-group row no-gutters">
                                <label class="col-sm-3 col-form-label mandatory">Role</label>
                                <div class="col-sm-9 mt-2" id="editUserRole">

                                    <p class="text-danger">{{$errors->has('role_id') ? $errors->first('role_id') : ''}}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="editUserStatus" class="col-sm-3 col-form-label">Status</label>
                                <div class="col-sm-9">
                                    <input type="checkbox" name="status" checked data-bootstrap-switch
                                           data-off-color="danger" data-on-color="success" id="editUserStatus">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-sm btn-info">Update</button>
                            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                        {{Form::close()}}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    {{-- Loading overlay plugin --}}
    <script src="{{asset('assets/admin/plugins/jquery-loading-overlay/loadingoverlay.min.js')}}"></script>
    <script>
        // all users in json format
        let users = @json($users);
        let user = @json(auth()->user());
        let divisions = [];
        let profileImagePath = "{{imagePath()['profile']['path']}}";

        $(document).ready(function () {
            //datatable
            $("#usersDataTable").DataTable({
                // "order": [[ 3, "asc" ]],
                "responsive": true,
                "autoWidth": false,
                "columnDefs": [
                    {"orderable": false, "targets": [6]}
                ],
                "pageLength": {{settings('per_page')}}
            });

            // select2
            $('.select2').select2();

            // ajax request for populate divisions, district and upazila
            // loader on
            // $("#loadingDiv").LoadingOverlay("show");
            //
            // $.ajax({
            //     type: 'GET',
            //     url: `/divisions/all`
            // }).done(function (res) {
            //     // loader off
            //     // $.LoadingOverlay("hide"); //full page
            //     $("#loadingDiv").LoadingOverlay("hide", true);
            //
            //     $.each(res.data, function (index, division) {
            //         divisions.push(division)
            //
            //         $('#division').append("<option value='" + division.id + "' >" + division.name + "</option>");
            //
            //         //populate districts according to user divison
            //         if (division.id == user.division_id) {
            //             $.each(division.districts, function (index, district) {
            //                 $('#district').append("<option value='" + district.id + "' >" + district.name + "</option>");
            //
            //                 //populate upazilas according to user district
            //                 if (district.id == user.district_id) {
            //                     $.each(districts.upazilas, function (index, upazila) {
            //                         $('#upazila').append("<option value='" + upazila.id + "' >" + upazila.name + "</option>");
            //                     });
            //                 }
            //             });
            //         }
            //     });
            // }).fail(function (err) {
            //     // loader off
            //     $("#loadingDiv").LoadingOverlay("hide", true);
            // });

            // //populate districts according to division
            // $('#division').change(function () {
            //     let divisionId = $(this).val();
            //     if (divisionId) {
            //         // loader on
            //         $("#loadingDiv").LoadingOverlay("show");
            //         $.ajax({
            //             type: 'GET',
            //             url: `/divisions/${divisionId}/districts`
            //         }).done(function (res) {
            //             // loader off
            //             $("#loadingDiv").LoadingOverlay("hide", true);
            //
            //             let districts = res.data;
            //             $("#district").empty();
            //             $("#upazila").empty();
            //             $("#district").append('<option value="">Select District</option>');
            //             $("#upazila").append('<option value="">Select Upazila</option>');
            //             $.each(districts, function (index, district) {
            //                 $('#district').append("<option value='" + district.id + "' >" + district.name + "</option>")
            //             });
            //         }).fail(function (err) {
            //             // loader off
            //             $("#loadingDiv").LoadingOverlay("hide", true);
            //         });
            //     }
            // });
            //
            // $('#editUserDivision').change(function () {
            //     let divisionId = $(this).val();
            //     if (divisionId) {
            //         // loader on
            //         $("#editLoadingDiv").LoadingOverlay("show");
            //         $.ajax({
            //             type: 'GET',
            //             url: `/divisions/${divisionId}/districts`
            //         }).done(function (res) {
            //             // loader off
            //             $("#editLoadingDiv").LoadingOverlay("hide", true);
            //
            //             let districts = res.data;
            //             $("#editUserDistrict").empty();
            //             $("#editUserUpazila").empty();
            //             $("#editUserDistrict").append('<option value="">Select District</option>');
            //             $("#editUserUpazila").append('<option value="">Select Upazila</option>');
            //             $.each(districts, function (index, district) {
            //                 $('#editUserDistrict').append("<option value='" + district.id + "' >" + district.name + "</option>")
            //             });
            //         }).fail(function (err) {
            //             // loader off
            //             $("#editLoadingDiv").LoadingOverlay("hide", true);
            //         });
            //     }
            // });
            //
            // // Populate upazilas according to district
            // $('#district').change(function () {
            //     let districtId = $(this).val();
            //     if (districtId) {
            //         // loader on
            //         $("#loadingDiv").LoadingOverlay("show");
            //         $.ajax({
            //             type: 'GET',
            //             url: `/districts/${districtId}/upazilas`
            //         }).done(function (res) {
            //             // loader off
            //             $("#loadingDiv").LoadingOverlay("hide", true);
            //
            //             let upazilas = res.data;
            //
            //             $("#upazila").empty();
            //             $("#upazila").append('<option value="">Select Upazila</option>');
            //             $.each(upazilas, function (index, upazila) {
            //                 $('#upazila').append("<option value='" + upazila.id + "' >" + upazila.name + "</option>")
            //             });
            //         }).fail(function (err) {
            //             // loader off
            //             $("#loadingDiv").LoadingOverlay("hide", true);
            //         });
            //         ;
            //     }
            // });
            //
            // $('#editUserDistrict').change(function () {
            //     let districtId = $(this).val();
            //     if (districtId) {
            //         // loader on
            //         $("#editLoadingDiv").LoadingOverlay("show");
            //         $.ajax({
            //             type: 'GET',
            //             url: `/districts/${districtId}/upazilas`
            //         }).done(function (res) {
            //             // loader off
            //             $("#editLoadingDiv").LoadingOverlay("hide", true);
            //
            //             let upazilas = res.data;
            //
            //             $("#editUserUpazila").empty();
            //             $("#editUserUpazila").append('<option value="">Select Upazila</option>');
            //             $.each(upazilas, function (index, upazila) {
            //                 $('#editUserUpazila').append("<option value='" + upazila.id + "' >" + upazila.name + "</option>")
            //             });
            //         }).fail(function (err) {
            //             // loader off
            //             $("#editLoadingDiv").LoadingOverlay("hide", true);
            //         });
            //         ;
            //     }
            // });


            // User create form
            $('#userCreateForm').validate({
                rules: {
                    first_name: {
                        required: true,
                    },
                    email: {
                        required: true,
                    },
                    password: {
                        required: true,
                        minlength: 6
                    },
                    password_confirmation: {
                        minlength: 6,
                        equalTo: "#password"
                    },
                    "role_id[]": {
                        required: true,
                    },
                },
                messages: {
                    first_name: {
                        required: "Please enter your first name",
                    },
                    email: {
                        required: "Please enter email address",
                    },
                    password: {
                        required: "Please enter your password",
                    },
                    password_confirmation: {
                        equalTo: "Confirm password doesn't match with password",
                    },
                    "role_id[]": {
                        required: "Please select role",
                    },
                    terms: "Please accept our terms"
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                    if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
            // User update form
            $('#userEditForm').validate({
                rules: {
                    first_name: {
                        required: true,
                    },
                    email: {
                        required: true,
                    },
                    password: {
                        required: true,
                        minlength: 6
                    },
                    password_confirmation: {
                        minlength: 6,
                        equalTo: "#password"
                    },
                    "role_id[]": {
                        required: true,
                    },
                },
                messages: {
                    first_name: {
                        required: "Please enter your first name",
                    },
                    email: {
                        required: "Please enter email address",
                    },
                    password: {
                        required: "Please enter your password",
                    },
                    password_confirmation: {
                        equalTo: "Confirm password doesn't match with password",
                    },
                    "role_id[]": {
                        required: "Please select role",
                    },
                    terms: "Please accept our terms"
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                    if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });

        // Functions
        function openShowUserModal(id) {
            let user = users.find(x => x.id == id);
            // sending value to modal
            $('#showUserId').html(user.id);
            $('#showUserFirstName').html(user.first_name);
            $('#showUserLastName').html(user.last_name);
            $('#showUserPhone').html(user.phone);
            $('#showUserEmail').html(user.email);
            // $('#showUserDivision').html(user.division ? user.division.name : '');
            // $('#showUserDistrict').html(user.district ? user.district.name : '');
            // $('#showUserUpazila').html(user.upazila ? user.upazila.name : '');
            $('#showUserAddress').html(user.address);

            if (user.avatar) {
                $('#showUserAvatar').attr('src', app_url + '/' + profileImagePath + '/' + user.avatar);
            } else {
                $('#showUserAvatar').attr('src', app_url + '/' + profileImagePath + '/' + 'default-profile.png');
            }


            $('#showUserStatus').html("");
            if (user.status == 1) {
                $('#showUserStatus').append("<span class='badge badge-success'>Active</span>");
            } else {
                $('#showUserStatus').append("<span class='badge badge-danger'>Inactive</span>");
            }

            $('#showUserRoles').html('');
            $.each(user.roles, function (index, value) {
                $('#showUserRoles').append(
                    // (index++ ? ', ' : '') +
                    "<span class='badge badge-success'>" + value.name + "</span> ");
            });
            $('#showUserCreatedAt').html(user.created_at);

            // Open modal
            $('#showUserModal').modal('show');
        }

        function openEditUserModal(id) {
            let roles = @json($roles);
            // Find user
            let user = users.find(x => x.id == id);
            $('#userEditForm').attr('action', app_url + '/admin/users/' + user.id);

            // sending value to modal
            $('#editUserFirstName').val(user.first_name);
            $('#editUserLastName').val(user.last_name);
            $('#editUserPhone').val(user.phone);
            $('#editUserEmail').val(user.email);
            $('#editUserAddress').html(user.address);

            if (user.avatar) {
                $('#editUserAvatar').attr('src', app_url + '/' + profileImagePath + '/' + user.avatar);
            } else {
                $('#editUserAvatar').attr('src', app_url + '/' + profileImagePath + '/' + 'default-profile.png');
            }

            user.status == 1 ? $('#editUserStatus').bootstrapSwitch('state', user.status, true) : $('#editUserStatus').bootstrapSwitch('state', user.status, false);

            $('#editUserRole').html('');
            $.each(roles, function (id, name) {// id = index, name = value
                var html = " <div class='icheck-success d-inline mr-5'> <input name='role_id[]' value=" + id + " type='checkbox' id=" + id + "  > " +
                    "<label for=" + id + ">" + name + "</label></div>"

                $('#editUserRole').append(html);
            });
            $.each(user.roles, function (index, value) {
                $("#" + value.id).prop("checked", true);
            });

            // $.each(divisions, function (index, division) {
            //     $('#editUserDivision').append("<option value='" + division.id + "' >" + division.name + "</option>")
            //
            //     //populate states according to user country
            //     if (division.id == user.division_id) {
            //         $.each(division.districts, function (index, district) {
            //             $('#editUserDistrict').append("<option value='" + district.id + "' >" + district.name + "</option>")
            //
            //             //populate cities according to user state
            //             if (district.id == user.district_id) {
            //                 $.each(district.upazilas, function (index, upazila) {
            //                     $('#editUserUpazila').append("<option value='" + upazila.id + "' >" + upazila.name + "</option>")
            //                 });
            //                 $('#editUserUpazila option[value=' + user.upazila_id + ']').attr("selected", "selected");
            //             }
            //         });
            //         $('#editUserDistrict option[value=' + user.district_id + ']').attr("selected", "selected");
            //     }
            // });
            // $('#editUserDivision option[value=' + user.division_id + ']').attr("selected", "selected");

            // Open modal
            $('#editUserModal').modal('show');
        }

        // Delete Service
        function deleteBtn(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    $('#deleteForm-' + id).submit();
                }
            })
        }

        function statusChange(id) {
            Swal.fire({
                title: 'Are you sure to change?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.value) {
                    window.location.href = $('#userStatus-' + id).data('href');
                }
            });
        }


    </script>
@endpush

