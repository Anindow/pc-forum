@extends('admin.layouts.app')
@section('title')
    Role
@endsection
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row no-gutters mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Manage Role</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">@lang('trans.dashboard')</a></li>
                        <li class="breadcrumb-item active">Role</li>
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
                        <h3 class="card-title float-left">All Roles
                        </h3>
                        @can('create-role')
                            <button type="button" onclick="openCreateRoleModal()"
                                    class="btn btn-info float-right btn-sm"><i
                                    class="fa fa-plus"></i> Add New
                            </button>
                        @endcan
                    </div>
                    <div class="card-body">
                        <table id="roleTable" class="table text-center table-bordered ">
                            <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th style="width: 25%">Name</th>
                                <th style="width: 15%">Created By</th>
                                <th style="width: 35%">Description</th>
                                <th style="width: 20%">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($i = 1)
                            @forelse($roles as $role)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$role->name}}</td>
                                    <td>{{$role->createdBy->full_name}}</td>
                                    <td>{{$role->description}}</td>
                                    <td>
{{--                                        @can('show-role')--}}

{{--                                        @endcan--}}
                                        @can('update-role')
                                            <button onclick="openEditRoleModal({{$role->id}})" type="button"
                                                    class="btn btn-sm btn-success" data-toggle="tooltip"
                                                    title="@lang('trans.edit')"><i class="fa fa-edit"></i></button>
                                        @endcan
                                        @can('delete-role')
                                            {{Form::open(['route'=>['roles.destroy', $role->id], 'method'=>'DELETE', 'id' => "deleteForm-$role->id", 'class' => 'd-inline'])}}
                                            <button type="button"
                                                    onclick="deleteBtn('{{$role->id}}')"
                                                    data-toggle="tooltip"
                                                    title="@lang('trans.delete')"
                                                    class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                            {{Form::close()}}
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <p class="text-center">No role yet</p>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modals -->
        <div class="modal fade" id="createRoleModal" tabindex="-1" aria-labelledby="createRoleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-xl ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createRoleModalLabel">Create role</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    {{Form::open(['route' => 'roles.store', 'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'roleCreateForm'])}}
                    <div class="modal-body">
                        <!-- text input -->
                        <div class="form-group row no-gutters">
                            <label for="name" class="col-sm-2 col-form-label">Role Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{old('name')}}" id="name" placeholder="Enter role name">
                                <span class="text-danger">{{$errors->has('name') ? $errors->first('name') : ''}}</span>

                            </div>
                        </div>
                        <div class="form-group row no-gutters">
                            <label for="description" class="col-sm-2 col-form-label">Description</label>
                            <div class="col-sm-10">
                                <textarea name="description" id="description" rows="3" class="form-control"
                                          placeholder="Enter description / optional"></textarea>
                            </div>
                        </div>

                        <div class="form-group row no-gutters">
                            <h2 class="col-12 text-center">Permissions</h2>
                            <div class="icheck-danger d-inline col-12 text-center">
                                <input type="checkbox" id="checkAll">
                                <label for="checkAll">Check All</label>
                            </div>
                        </div>
                        <div class="form-group row no-gutters">
                            <label for="createUserPermission" class="col-sm-2 col-form-label">Users Permission</label>
                            <div class="col-sm-10" id="createUserPermission">
                            </div>
                        </div>
                        <div class="form-group row no-gutters">
                            <label for="createRolePermission" class="col-sm-2 col-form-label">Roles Permission</label>
                            <div class="col-sm-10" id="createRolePermission">
                            </div>
                        </div>
                        <div class="form-group row no-gutters">
                            <label for="createProductPermission" class="col-sm-2 col-form-label">Product Permission</label>
                            <div class="col-sm-10" id="createProductPermission">
                            </div>
                        </div>
                        <div class="form-group row no-gutters">
                            <label for="createProductLinkPermission" class="col-sm-2 col-form-label">Product Link Permission</label>
                            <div class="col-sm-10" id="createProductLinkPermission">
                            </div>
                        </div>
                        <div class="form-group row no-gutters">
                            <label for="description" class="col-sm-2 col-form-label">Others Permission</label>
                            <div class="col-sm-10" id="createOtherPermission">
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

        <div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editRoleModalLabel">Edit role</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    {{Form::open(['id' => 'roleEditForm', 'method' => 'PATCH', 'class' => 'form-horizontal'])}}
                    <div class="modal-body">
                        <div class="form-group row no-gutters">
                            <label for="editRoleName" class="col-sm-2 col-form-label">Role Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       id="editRoleName" placeholder="Enter role name">
                                <span class="text-danger">{{$errors->has('name') ? $errors->first('name') : ''}}</span>

                            </div>
                        </div>
                        <div class="form-group row no-gutters">
                            <label for="editRoleDescription" class="col-sm-2 col-form-label">Description</label>
                            <div class="col-sm-10">
                                <textarea name="description" id="editRoleDescription" rows="3" class="form-control"
                                          placeholder="Enter description / optional"></textarea>
                            </div>
                        </div>

                        <div class="form-group row no-gutters">
                            <h2 class="col-12 text-center">Permissions</h2>
                            <div class="icheck-danger d-inline col-12 text-center">
                                <input type="checkbox" id="checkAllEdit">
                                <label for="checkAll">Check All</label>
                            </div>
                        </div>
                        <div class="form-group row no-gutters">
                            <label for="editUserPermission" class="col-sm-2 col-form-label">Users Permission</label>
                            <div class="col-sm-10" id="editUserPermission">
                            </div>
                        </div>
                        <div class="form-group row no-gutters">
                            <label for="editRolePermission" class="col-sm-2 col-form-label">Roles Permission</label>
                            <div class="col-sm-10" id="editRolePermission">
                            </div>
                        </div>
                        <div class="form-group row no-gutters">
                            <label for="editProductPermission" class="col-sm-2 col-form-label">Product Permission</label>
                            <div class="col-sm-10" id="editProductPermission">
                            </div>
                        </div>
                        <div class="form-group row no-gutters">
                            <label for="editProductLinkPermission" class="col-sm-2 col-form-label">Product Link Permission</label>
                            <div class="col-sm-10" id="editProductLinkPermission">
                            </div>
                        </div>
                        <div class="form-group row no-gutters">
                            <label for="editOtherPermission" class="col-sm-2 col-form-label">Others Permission</label>
                            <div class="col-sm-10" id="editOtherPermission">
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
    </section>
@endsection

@push('script')
    <script>
        $("#checkAll").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });

        $("#checkAllEdit").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });


        // all users in json format
        let permissions = @json($permissions);

        $(document).ready(function () {

            $("#roleTable").DataTable({
                "responsive": true,
                "autoWidth": false,
                "columnDefs": [
                    {"orderable": false, "targets": [4]}
                ],
                "pageLength": {{settings('per_page')}}
            });

        });

        // Category create form
        $('#roleCreateForm').validate({
            rules: {
                name: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: "Please enter a role name",
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
        $('#roleEditForm').validate({
            rules: {
                name: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: "Please enter a role name",
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

        // Functions
        function openCreateRoleModal() {
            // Reset all
            $('#createUserPermission').html('');
            $('#createRolePermission').html('');
            $('#createPostPermission').html('');
            $('#createProductPermission').html('');
            $('#createProductLinkPermission').html('');
            $('#createOtherPermission').html('');

            $.each(permissions, function (index, permission) {
                if (permission.for === 'user') {
                    $('#createUserPermission').append(
                        "<div class='icheck-success d-inline mr-2'>" +
                        "<input type='checkbox' name='permission_id[]' value='" + permission.id + "' id='" + 'create' + permission.id + "'>" +
                        "<label for='" + 'create' + permission.id + "'>" + permission.name + "</label></div>");
                }

                if (permission.for === 'role') {
                    $('#createRolePermission').append(
                        "<div class='icheck-success d-inline mr-2'>" +
                        "<input type='checkbox' name='permission_id[]' value='" + permission.id + "' id='" + 'create' + permission.id + "'>" +
                        "<label for='" + 'create' + permission.id + "'>" + permission.name + "</label></div>");
                }

                if (permission.for === 'product') {
                    $('#createProductPermission').append(
                        "<div class='icheck-success d-inline mr-2'>" +
                        "<input type='checkbox' name='permission_id[]' value='" + permission.id + "' id='" + 'create' + permission.id + "'>" +
                        "<label for='" + 'create' + permission.id + "'>" + permission.name + "</label></div>");
                }

                if (permission.for === 'product-link') {
                    $('#createProductLinkPermission').append(
                        "<div class='icheck-success d-inline mr-2'>" +
                        "<input type='checkbox' name='permission_id[]' value='" + permission.id + "' id='" + 'create' + permission.id + "'>" +
                        "<label for='" + 'create' + permission.id + "'>" + permission.name + "</label></div>");
                }

                if (permission.for === 'other') {
                    $('#createOtherPermission').append(
                        "<div class='icheck-success d-inline mr-2'>" +
                        "<input type='checkbox' name='permission_id[]' value='" + permission.id + "' id='" + 'create' + permission.id + "'>" +
                        "<label for='" + 'create' + permission.id + "'>" + permission.name + "</label></div>");
                }
            });

            // Open modal
            $('#createRoleModal').modal('show');
        }


        function openEditRoleModal(id) {
            let roles = @json($roles);
            let role = roles.find(x => x.id == id);

            // Set edit form action url
            $('#roleEditForm').attr('action', app_url + '/admin/roles/' + role.id);

            // Set update row value
            $('#editRoleName').val(role.name);
            $('#editRoleDescription').val(role.description);


            // Reset all
            $('#editUserPermission').html('');
            $('#editRolePermission').html('');
            $('#editPostPermission').html('');
            $('#editProductPermission').html('');
            $('#editProductLinkPermission').html('');
            $('#editOtherPermission').html('');


            $.each(permissions, function (index, permission) {
                if (permission.for === 'user') {
                    $('#editUserPermission').append(
                        "<div class='icheck-success d-inline mr-2'>" +
                        "<input type='checkbox' name='permission_id[]' value='" + permission.id + "' id='" + permission.id + "'>" +
                        "<label for='" + permission.id + "'>" + permission.name + "</label></div>");
                }

                if (permission.for === 'role') {
                    $('#editRolePermission').append(
                        "<div class='icheck-success d-inline mr-2'>" +
                        "<input type='checkbox' name='permission_id[]' value='" + permission.id + "' id='" + permission.id + "'>" +
                        "<label for='" + permission.id + "'>" + permission.name + "</label></div>");
                }

                if (permission.for === 'product') {
                    $('#editProductPermission').append(
                        "<div class='icheck-success d-inline mr-2'>" +
                        "<input type='checkbox' name='permission_id[]' value='" + permission.id + "' id='" + permission.id + "'>" +
                        "<label for='" + permission.id + "'>" + permission.name + "</label></div>");
                }
                if (permission.for === 'product-link') {
                    $('#editProductLinkPermission').append(
                        "<div class='icheck-success d-inline mr-2'>" +
                        "<input type='checkbox' name='permission_id[]' value='" + permission.id + "' id='" + permission.id + "'>" +
                        "<label for='" + permission.id + "'>" + permission.name + "</label></div>");
                }

                if (permission.for === 'other') {
                    $('#editOtherPermission').append(
                        "<div class='icheck-success d-inline mr-2'>" +
                        "<input type='checkbox' name='permission_id[]' value='" + permission.id + "' id='" + permission.id + "'>" +
                        "<label for='" + permission.id + "'>" + permission.name + "</label></div>");
                }
            });

            // Checked all role permissions
            $.each(role.permissions, function (index, value) {
                $("#" + value.id).prop("checked", true);
            });
            // Open modal
            $('#editRoleModal').modal('show');
        }

        // Delete Role
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

    </script>
@endpush
