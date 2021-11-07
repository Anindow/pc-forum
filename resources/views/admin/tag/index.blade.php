@extends('admin.layouts.app')
@section('title')
    @lang('trans.tag')
@endsection

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row no-gutters mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">@lang('trans.manage_tag')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">@lang('trans.dashboard')</a></li>
                        <li class="breadcrumb-item active">@lang('trans.tag')</li>
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
                        <h3 class="card-title float-left">@lang('trans.all_tags')
                        </h3>
                        <button type="button"
                                data-toggle="modal"
                                data-target="#createTagModal"
                                class="btn btn-info float-right btn-sm">
                            <i class="fa fa-plus"></i> @lang('trans.add_new')
                        </button>
                    </div>
                    <div class="card-body">
                        {{--                        <table id="categoriesTable" class="table table-bordered table-striped dataTable dtr-inline text-center">--}}
                        <table id="tagTable" class="table text-center table-bordered ">
                            <thead>
                            <tr>
                                <th style="width: 10%">No</th>
                                <th style="width: 40%">Name</th>
                                <th style="width: 25%">Status</th>
                                <th style="width: 25%">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($i = 1)
                            @foreach($tags as $tag)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$tag->name}}</td>
                                    <td>
                                        <a href="javascript:void(0)"
                                           @can('access-tag')
                                           onclick="tagStatusChange('{{$tag->slug}}')"
                                           @endcan
                                           data-href="{{route('tags.status.update', $tag->slug)}}"
                                           data-toggle="tooltip"
                                           title="@lang('trans.change_status')"
                                           id="tagStatus-{{$tag->slug}}"
                                        >
                                            <span
                                                class="badge {{$tag->status == 1 ? 'badge-success' : 'badge-danger'}}">
                                            {{$tag->status == 1 ? 'Active' : 'Inactive' }}
                                        </span>
                                        </a>
                                    </td>
                                    <td>
                                        <button onclick="openShowTagModal('{{$tag->slug}}')" type="button"
                                                class="btn btn-sm btn-primary" data-toggle="tooltip"
                                                title="@lang('trans.view')"><i class="fa fa-search-plus"></i></button>
                                        <button onclick="openEditTagModal('{{$tag->slug}}')" type="button"
                                                class="btn btn-sm btn-success" data-toggle="tooltip"
                                                title="@lang('trans.edit')"><i class="fa fa-edit"></i></button>

                                        {{Form::open(['route'=>['tags.destroy', $tag->slug], 'method'=>'DELETE', 'id' => "deleteForm-$tag->slug", 'class' => 'd-inline'])}}
                                        <button type="button"
                                                @can('access-tag')
                                                onclick="deleteBtn('{{$tag->slug}}')"
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
        <div class="modal fade" id="createTagModal" tabindex="-1" aria-labelledby="createTagModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createTagModalLabel">@lang('trans.create_tag')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    {{Form::open(['route' => 'tags.store', 'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'tagCreateForm'])}}
                    <div class="modal-body">
                        <div class="form-group row no-gutters">
                            <label for="name" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{old('name')}}" id="name" placeholder="Enter tag name">
                                @error('name')<span class="text-danger">{{$errors->first('name')}}</span>@enderror

                            </div>
                        </div>

                        <div class="form-group row no-gutters">
                            <label for="status" class="col-sm-2 col-form-label">Status</label>
                            <div class="col-sm-10">
                                <input type="checkbox" id="status" checked name="status" data-bootstrap-switch
                                       data-off-color="danger" data-on-color="info">
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

        <div class="modal fade" id="editTagModal" tabindex="-1" aria-labelledby="editTagModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTagModalLabel">@lang('trans.edit_tag')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    {{Form::open(['id' => 'tagEditForm', 'method' => 'PATCH', 'class' => 'form-horizontal'])}}
                    <div class="modal-body">
                        <div class="form-group row no-gutters">
                            <label for="editTagName" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       id="editTagName" placeholder="Enter tag name">
                                @error('name')<span class="text-danger">{{$errors->first('name')}}</span>@enderror
                            </div>
                        </div>

                        <div class="form-group row no-gutters">
                            <label for="editTagStatus" class="col-sm-2 col-form-label">Status</label>
                            <div class="col-sm-10">
                                <input type="checkbox" id="editTagStatus" name="status" data-bootstrap-switch
                                       data-off-color="danger" data-on-color="info">
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

        <div class="modal fade" id="showTagModal" tabindex="-1" aria-labelledby="showTagModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="showTagModalLabel">@lang('trans.show_tag')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-borderless">
                            <tbody>
                            <tr>
                                <th>Name:</th>
                                <td id="showTagName"></td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td id="showTagStatus"></td>
                            </tr>
                            {{--                            <tr>--}}
                            {{--                                <th>Creation Date:</th>--}}
                            {{--                                <td id="showCategoryCreatedAt"></td>--}}
                            {{--                            </tr>--}}
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script>
        // all tags in json format
        let tags = @json($tags);

        $(document).ready(function () {
            //datatable
            $("#tagTable").DataTable({
                "responsive": true,
                "autoWidth": false,
                "columnDefs": [
                    {"orderable": false, "targets": [3]}
                ],
                "pageLength": {{settings('per_page')}}
            });

            // Tag create form
            $('#tagCreateForm').validate({
                rules: {
                    name: {
                        required: true,
                    },
                },
                messages: {
                    name: {
                        required: "Please enter a tag name",
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

            // Tag create form
            $('#tagEditForm').validate({
                rules: {
                    name: {
                        required: true,
                    },
                },
                messages: {
                    name: {
                        required: "Please enter a tag name",
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
        function openShowTagModal(slug) {
            let tag = tags.find(tag => tag.slug == slug);
            // Set update row value
            $('#showTagName').html(tag.name);
            $('#showTagStatus').html('');
            $('#showTagStatus').append(tag.status === 1 ? "<span class='badge badge-success'>Active</span>" : "<span class='badge badge-danger'>Inactive</span>");

            // Open modal
            $('#showTagModal').modal('show');
        }

        function openEditTagModal(slug) {
            let tag = tags.find(tag => tag.slug == slug);

            // Set edit form action url
            $('#tagEditForm').attr('action', app_url + '/admin/tags/' + tag.slug);

            // Set update row value
            $('#editTagName').val(tag.name);

            tag.status == 1 ? $('#editTagStatus').bootstrapSwitch('state', tag.status, true) : $('#editTagStatus').bootstrapSwitch('state', tag.status, false);
            ;

            // Open modal
            $('#editTagModal').modal('show');

        }

        // Delete Tag
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
            });
        }

        // Tag Status Change
        function tagStatusChange(slug) {
            Swal.fire({
                title: 'Are you sure to change?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.value) {
                    window.location.href = $('#tagStatus-' + slug).data('href');
                }
            });
        }
    </script>
@endpush
