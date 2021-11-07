@extends('admin.layouts.app')
@section('title')
    @lang('trans.brand')
@endsection

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row no-gutters mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">@lang('trans.manage_brand')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">@lang('trans.dashboard')</a></li>
                        <li class="breadcrumb-item active">@lang('trans.brand')</li>
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
                        <h3 class="card-title float-left">@lang('trans.all_brands')
                        </h3>
                        <button type="button"
                                data-toggle="modal"
                                data-target="#createBrandModal"
                                class="btn btn-info float-right btn-sm">
                            <i class="fa fa-plus"></i> @lang('trans.add_new')
                        </button>
                    </div>
                    <div class="card-body">
                        <table id="brandTable" class="table text-center table-bordered ">
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
                            @foreach($brands as $brand)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$brand->name}}</td>
                                    <td>
                                        <a href="javascript:void(0)"
                                           @can('access-brand')
                                           onclick="brandStatusChange('{{$brand->slug}}')"
                                           @endcan
                                           data-href="{{route('brands.status.update', $brand->slug)}}"
                                           data-toggle="tooltip"
                                           title="@lang('trans.change_status')"
                                           id="brandStatus-{{$brand->slug}}"
                                        >
                                            <span
                                                class="badge {{$brand->status == 1 ? 'badge-success' : 'badge-danger'}}">
                                            {{$brand->status == 1 ? 'Active' : 'Inactive' }}
                                        </span>
                                        </a>
                                    </td>
                                    <td>
                                        <button onclick="openShowBrandModal('{{$brand->slug}}')" type="button"
                                                class="btn btn-sm btn-primary" data-toggle="tooltip"
                                                title="@lang('trans.view')"><i class="fa fa-search-plus"></i></button>
                                        <button onclick="openEditBrandModal('{{$brand->slug}}')" type="button"
                                                class="btn btn-sm btn-success" data-toggle="tooltip"
                                                title="@lang('trans.edit')"><i class="fa fa-edit"></i></button>

                                        {{Form::open(['route'=>['brands.destroy', $brand->slug], 'method'=>'DELETE', 'id' => "deleteForm-$brand->slug", 'class' => 'd-inline'])}}
                                        <button type="button"
                                                @can('access-brand')
                                                onclick="deleteBtn('{{$brand->slug}}')"
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
        <div class="modal fade" id="createBrandModal" tabindex="-1" aria-labelledby="createBrandModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createBrandModalLabel">@lang('trans.create_brand')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    {{Form::open(['route' => 'brands.store', 'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'brandCreateForm'])}}
                    <div class="modal-body">
                        <div class="form-group row no-gutters">
                            <label for="name" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{old('name')}}" id="name" placeholder="Enter brand name">
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

        <div class="modal fade" id="editBrandModal" tabindex="-1" aria-labelledby="editBrandModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editBrandModalLabel">@lang('trans.edit_brand')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    {{Form::open(['id' => 'brandEditForm', 'method' => 'PATCH', 'class' => 'form-horizontal'])}}
                    <div class="modal-body">
                        <div class="form-group row no-gutters">
                            <label for="editBrandName" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       id="editBrandName" placeholder="Enter brand name">
                                @error('name')<span class="text-danger">{{$errors->first('name')}}</span>@enderror
                            </div>
                        </div>

                        <div class="form-group row no-gutters">
                            <label for="editBrandStatus" class="col-sm-2 col-form-label">Status</label>
                            <div class="col-sm-10">
                                <input type="checkbox" id="editBrandStatus" name="status" data-bootstrap-switch
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

        <div class="modal fade" id="showBrandModal" tabindex="-1" aria-labelledby="showBrandModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="showBrandModalLabel">@lang('trans.show_brand')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-borderless">
                            <tbody>
                            <tr>
                                <th>Name:</th>
                                <td id="showBrandName"></td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td id="showBrandStatus"></td>
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
    </section>
@endsection

@push('script')
    <script>
        // all Brands in json format
        let brands = @json($brands);

        $(document).ready(function () {
            //datatable
            $("#brandTable").DataTable({
                "responsive": true,
                "autoWidth": false,
                "columnDefs": [
                    {"orderable": false, "targets": [3]}
                ],
                "pageLength": {{settings('per_page')}}
            });

            // brand create form
            $('#brandCreateForm').validate({
                rules: {
                    name: {
                        required: true,
                    },
                },
                messages: {
                    name: {
                        required: "Please enter a brand name",
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

            // Brand edit form
            $('#brandEditForm').validate({
                rules: {
                    name: {
                        required: true,
                    },
                },
                messages: {
                    name: {
                        required: "Please enter a brand name",
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
        function openShowBrandModal(slug) {
            let brand = brands.find(brand => brand.slug == slug);
            // Set update row value
            $('#showBrandName').html(brand.name);
            $('#showBrandStatus').html('');
            $('#showBrandStatus').append(brand.status === 1 ? "<span class='badge badge-success'>Active</span>" : "<span class='badge badge-danger'>Inactive</span>");

            // Open modal
            $('#showBrandModal').modal('show');
        }

        function openEditBrandModal(slug) {
            let brand = brands.find(brand => brand.slug == slug);

            // Set edit form action url
            $('#brandEditForm').attr('action', app_url + '/admin/brands/' + brand.slug);

            // Set update row value
            $('#editBrandName').val(brand.name);

            brand.status == 1 ? $('#editBrandStatus').bootstrapSwitch('state', brand.status, true) : $('#editBrandStatus').bootstrapSwitch('state', brand.status, false);
            ;

            // Open modal
            $('#editBrandModal').modal('show');

        }

        // Delete Brand
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

        // Brand Status Change
        function brandStatusChange(slug) {
            Swal.fire({
                title: 'Are you sure to change?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.value) {
                    window.location.href = $('#brandStatus-' + slug).data('href');
                }
            });
        }
    </script>
@endpush
