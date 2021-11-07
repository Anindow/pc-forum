@extends('admin.layouts.app')
@section('title')
    @lang('trans.category')
@endsection
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row no-gutters mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">@lang('trans.manage_category')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">@lang('trans.dashboard')</a></li>
                        <li class="breadcrumb-item active">@lang('trans.category')</li>
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
                        <h3 class="card-title float-left">@lang('trans.all_categories')
                        </h3>
                        <button type="button"
                                data-toggle="modal"
                                data-target="#createCategoryModal"
                                class="btn btn-info float-right btn-sm">
                            <i class="fa fa-plus"></i> @lang('trans.add_new')
                        </button>
                    </div>
                    <div class="card-body">
                        {{--                        <table id="categoriesTable" class="table table-bordered table-striped dataTable dtr-inline text-center">--}}
                        <table id="categoryTable" class="table text-center table-bordered ">
                            <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th style="width: 25%">Name</th>
                                <th style="width: 40%">Description</th>
                                <th style="width: 10%">Status</th>
                                <th style="width: 20%">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($i = 1)
                            @foreach($categories as $category)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>
                                        @if(isset($category->category_id))
                                            @include('admin.category.child', ['category' => $category])
                                        @endif
                                        {{$category->name}}
                                    </td>
                                    <td>{{$category->description}}</td>
                                    <td>
                                        <a href="javascript:void(0)"
                                           @can('access-category')
                                           onclick="categoryStatusChange('{{$category->slug}}')"
                                           @endcan
                                           data-href="{{route('categories.status.update', $category->slug)}}"
                                           data-toggle="tooltip"
                                           title="@lang('trans.change_status')"
                                           id="categoryStatus-{{$category->slug}}"
                                        >
                                            <span
                                                class="badge {{$category->status == 1 ? 'badge-success' : 'badge-danger'}}">
                                            {{$category->status == 1 ? 'Active' : 'Inactive' }}
                                        </span>
                                        </a>
                                    </td>
                                    <td>
                                        <button onclick="openShowCategoryModal('{{$category->slug}}')" type="button"
                                                class="btn btn-sm btn-primary" data-toggle="tooltip"
                                                title="@lang('trans.view')"><i class="fa fa-search-plus"></i></button>
                                        <button onclick="openEditCategoryModal('{{$category->slug}}')" type="button"
                                                class="btn btn-sm btn-success" data-toggle="tooltip"
                                                title="@lang('trans.edit')"><i class="fa fa-edit"></i></button>

                                        {{Form::open(['route'=>['categories.destroy', $category->slug], 'method'=>'DELETE', 'id' => "deleteForm-$category->slug", 'class' => 'd-inline'])}}
                                        <button type="button"
                                                @can('access-category')
                                                onclick="deleteBtn('{{$category->slug}}')"
                                                @endcan
                                                class="btn btn-sm btn-danger" data-toggle="tooltip"
                                                title="@lang('trans.delete')"><i class="fa fa-trash"></i></button>
                                        {{Form::close()}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modals -->
        <div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createCategoryModalLabel">@lang('trans.create_category')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    {{Form::open(['route' => 'categories.store', 'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'categoryCreateForm'])}}
                    <div class="modal-body">
                        <div class="form-group row no-gutters">
                            <label for="name" class="col-sm-3 col-form-label mandatory">Name</label>
                            <div class="col-sm-9">
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{old('name')}}" id="name" placeholder="Enter category name">
                                @error('name')<span class="text-danger">{{$errors->first('name')}}</span>@enderror

                            </div>
                        </div>
                        <div class="form-group row no-gutters">
                            <label for="category_id" class="col-sm-3 col-form-label">Parent Category</label>
                            <div class="col-sm-9">
                                <select
                                    class="form-control select2 select2-info"
                                    data-dropdown-css-class="select2-info"
                                    name="category_id" id="editUserUpazila" style="width: 100%;">
                                    <option value="">Select category (optional)</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row no-gutters">
                            <label for="description" class="col-sm-3 col-form-label">Description</label>
                            <div class="col-sm-9">
                                <textarea name="description" id="description" class="form-control"
                                          placeholder="Enter description (optional)"></textarea>
                            </div>
                        </div>
                        <div class="form-group row no-gutters">
                            <label for="status" class="col-sm-3 col-form-label">Status</label>
                            <div class="col-sm-9">
                                <input type="checkbox" id="status" checked name="status" data-bootstrap-switch
                                       data-off-color="danger" data-on-color="info">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-sm btn-info">@lang('trans.create')</button>
                            <button type="button" class="btn btn-sm btn-secondary"
                                    data-dismiss="modal">@lang('trans.close')</button>
                        </div>
                        {{Form::close()}}
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCategoryModalLabel">@lang('trans.edit_category')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    {{Form::open(['id' => 'categoryEditForm', 'method' => 'PATCH', 'class' => 'form-horizontal'])}}
                    <div class="modal-body">
                        <div class="form-group row no-gutters">
                            <label for="editCategoryName" class="col-sm-3 col-form-label">Name</label>
                            <div class="col-sm-9">
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       id="editCategoryName" placeholder="Enter category name">
                                @error('name')<span class="text-danger">{{$errors->first('name')}}</span>@enderror
                            </div>
                        </div>
                        <div class="form-group row no-gutters">
                            <label for="editParentCategory" class="col-sm-3 col-form-label">Parent Category</label>
                            <div class="col-sm-9">
                                <select
                                    class="form-control select2 select2-info"
                                    data-dropdown-css-class="select2-info"
                                    name="category_id" id="editParentCategory" style="width: 100%;">
                                    <option value="">Select category(optional)</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row no-gutters">
                            <label for="editCategoryDescription" class="col-sm-3 col-form-label">Description</label>
                            <div class="col-sm-9">
                                <textarea name="description" id="editCategoryDescription" class="form-control"
                                          placeholder="Enter description (optional)"></textarea>
                            </div>
                        </div>
                        {{--                        <div class="form-group row no-gutters">--}}
                        {{--                            <label for="is_parent" class="col-sm-3 col-form-label">Is Parent</label>--}}
                        {{--                            <div class="col-sm-9 mt-2">--}}
                        {{--                                <div class="icheck-info d-inline">--}}
                        {{--                                    <input type="checkbox" id="is_parent">--}}
                        {{--                                    <label for="is_parent">--}}
                        {{--                                    </label>--}}
                        {{--                                </div>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        <div class="form-group row no-gutters">
                            <label for="editCategoryStatus" class="col-sm-3 col-form-label">Status</label>
                            <div class="col-sm-9">
                                <input type="checkbox" id="editCategoryStatus" name="status" data-bootstrap-switch
                                       data-off-color="danger" data-on-color="info">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-info">@lang('trans.update')</button>
                        <button type="button" class="btn btn-sm btn-secondary"
                                data-dismiss="modal">@lang('trans.close')</button>
                    </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
        <div class="modal fade" id="showCategoryModal" tabindex="-1" aria-labelledby="showCategoryModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="showCategoryModalLabel">@lang('trans.show_category')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-borderless">
                            <tbody>
                            <tr>
                                <th>Name:</th>
                                <td id="showCategoryName"></td>
                            </tr>
                            <tr>
                                <th>Parent Category:</th>
                                <td id="showCategoryParentCategory"></td>
                            </tr>
                            <tr>
                                <th>Description:</th>
                                <td id="showCategoryDescription"></td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td id="showCategoryStatus"></td>
                            </tr>
                            {{--                            <tr>--}}
                            {{--                                <th>Creation Date:</th>--}}
                            {{--                                <td id="showCategoryCreatedAt"></td>--}}
                            {{--                            </tr>--}}
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary"
                                data-dismiss="modal">@lang('trans.close')</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script>
        // all categories in json format
        let categories = @json($categories);

        $(document).ready(function () {
            //datatable
            $("#categoryTable").DataTable({
                "responsive": true,
                "autoWidth": false,
                "columnDefs": [
                    {"orderable": false, "targets": [4]}
                ],
                "pageLength": {{settings('per_page')}}
            });

            // select2
            $('.select2').select2()

            // Category create form
            $('#categoryCreateForm').validate({
                rules: {
                    name: {
                        required: true,
                    },
                },
                messages: {
                    name: {
                        required: "Please enter a category name",
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

            // Category create form
            $('#categoryEditForm').validate({
                rules: {
                    name: {
                        required: true,
                    },
                },
                messages: {
                    name: {
                        required: "Please enter a category name",
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
        function openShowCategoryModal(slug) {
            let category = categories.find(category => category.slug == slug);

            // update id values
            $('#showCategoryName').html(category.name);
            $('#showCategoryDescription').html(category.description);
            let parent = category.category ? category.category.name : ''
            $('#showCategoryParentCategory').html(parent);

            $('#showCategoryStatus').html('');
            $('#showCategoryStatus').append(category.status === 1 ? "<span class='badge badge-success'>Active</span>" : "<span class='badge badge-danger'>Inactive</span>");

            // Open modal
            $('#showCategoryModal').modal('show');
        }

        function openEditCategoryModal(slug) {
            let category = categories.find(category => category.slug == slug);

            // Set edit form action url
            $('#categoryEditForm').attr('action', app_url + '/admin/categories/' + category.slug);

            // Set update row value
            $('#editCategoryName').val(category.name);
            $('#editCategoryDescription').val(category.description);

            $("#editParentCategory").empty();
            $("#editParentCategory").append('<option value="">Select category(optional)</option>');
            $.each(categories, function (index, category) {
                $('#editParentCategory').append("<option value='" + category.id + "' >" + category.name + "</option>")
            });
            $('#editParentCategory option[value=' + category.category_id + ']').attr("selected", "selected");

            category.status == 1 ? $('#editCategoryStatus').bootstrapSwitch('state', category.status, true) : $('#editCategoryStatus').bootstrapSwitch('state', category.status, false);
            ;

            // Open modal
            $('#editCategoryModal').modal('show');

        }

        // Delete Category
        function deleteBtn(slug) {
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
                    $('#deleteForm-' + slug).submit();
                }
            })
        }

        // Category Status Change
        function categoryStatusChange(slug) {
            Swal.fire({
                title: 'Are you sure to change?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.value) {
                    window.location.href = $('#categoryStatus-' + slug).data('href');
                }
            });
        }
    </script>
@endpush
