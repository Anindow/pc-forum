@extends('admin.layouts.app')
@section('title')
    @lang('trans.shop')
@endsection
{{--@push('style')--}}
{{--    <style>--}}
{{--        .brand-image {--}}
{{--            height: 81px;--}}
{{--            width: 58px;--}}
{{--        }--}}
{{--    </style>--}}
{{--@endpush--}}

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row no-gutters mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">@lang('trans.manage_shop')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">@lang('trans.dashboard')</a></li>
                        <li class="breadcrumb-item active">@lang('trans.shop')</li>
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
                        <h3 class="card-title float-left">@lang('trans.all_shops')
                        </h3>
                        <button type="button"
                                data-toggle="modal"
                                data-target="#createShopModal"
                                class="btn btn-info float-right btn-sm">
                            <i class="fa fa-plus"></i> @lang('trans.add_new')
                        </button>
                    </div>
                    <div class="card-body">
                        <table id="shopTable" class="table text-center table-bordered ">
                            <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th style="width: 15%">Name</th>
                                <th style="width: 15%">Logo Image</th>
                                <th style="width: 30%">Address</th>
                                <th style="width: 15%">Status</th>
                                <th style="width: 20%">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($i = 1)
                            @foreach($shops as $shop)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$shop->name}}</td>
                                    <td>
{{--                                        <img src="{{asset('assets/images/brand/'.$shop->logo_image)}}" alt="Brand image" class="brand-image">--}}
                                        <img src="{{getImage(imagePath()['brand']['path'] . '/' . $shop->logo_image, imagePath()['brand']['size'])}}" alt="Brand image" class="brand-image">
                                    </td>
                                    <td>{{$shop->address}}</td>
                                    <td>
                                        <a href="javascript:void(0)"
                                           @can('access-shop')
                                           onclick="shopStatusChange('{{$shop->slug}}')"
                                           @endcan
                                           data-href="{{route('shops.status.update', $shop->slug)}}"
                                           data-toggle="tooltip"
                                           title="@lang('trans.change_status')"
                                           id="shopStatus-{{$shop->slug}}"
                                        >
                                            <span
                                                class="badge {{$shop->status == 1 ? 'badge-success' : 'badge-danger'}}">
                                            {{$shop->status == 1 ? 'Active' : 'Inactive' }}
                                        </span>
                                        </a>
                                    </td>
                                    <td>
                                        <button onclick="openShowShopModal('{{$shop->slug}}')" type="button"
                                                class="btn btn-sm btn-primary" data-toggle="tooltip"
                                                title="@lang('trans.view')"><i class="fa fa-search-plus"></i></button>
                                        <button onclick="openEditShopModal('{{$shop->slug}}')" type="button"
                                                class="btn btn-sm btn-success" data-toggle="tooltip"
                                                title="@lang('trans.edit')"><i class="fa fa-edit"></i></button>

                                        {{Form::open(['route'=>['shops.destroy', $shop->slug], 'method'=>'DELETE', 'id' => "deleteForm-$shop->slug", 'class' => 'd-inline'])}}
                                        <button type="button"
                                                @can('access-shop')
                                                onclick="deleteBtn('{{$shop->slug}}')"
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
        <div class="modal fade" id="createShopModal" tabindex="-1" aria-labelledby="createShopModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createShopModalLabel">@lang('trans.create_shop')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    {{Form::open(['route' => 'shops.store', 'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'shopCreateForm', 'enctype' => 'multipart/form-data'])}}
                    <div class="modal-body">
                        <div class="form-group row no-gutters">
                            <label for="name" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{old('name')}}" id="name" placeholder="Enter shop name">
                                @error('name')<span class="text-danger">{{$errors->first('name')}}</span>@enderror

                            </div>
                        </div>

                        <div class="form-group row no-gutters">
                            <label for="address" class="col-sm-2 col-form-label">Address</label>
                            <div class="col-sm-10">
                                <textarea name="address" id="address" class="form-control"
                                          placeholder="Enter address (optional)"></textarea>
                            </div>
                        </div>


                        <div class="form-group row no-gutters">
                            <label for="logo_image" class="col-sm-2 col-form-label" id="inputGroupFileAddon05">Logo</label>
                            <div class="col-sm-10">
                                <div class="custom-file">
                                    <input type="file" name="logo_image" class="custom-file-input @error('name') logo_image @enderror" accept="image/jpeg, image/png" id="logo_image" aria-describedby="inputGroupFileAddon05">
                                    <label class="custom-file-label" for="logo_image">Choose logo</label>
                                </div>
                                @error('logo_image')<span class="text-danger">{{$errors->first('logo_image')}}</span>@enderror
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

        <div class="modal fade" id="editShopModal" tabindex="-1" aria-labelledby="editShopModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editShopModalLabel">@lang('trans.edit_shop')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    {{Form::open(['id' => 'shopEditForm', 'method' => 'PATCH', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data'])}}
                    <div class="modal-body">
                        <div class="form-group row no-gutters">
                            <label for="editShopName" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       id="editShopName" placeholder="Enter shop name">
                                @error('name')<span class="text-danger">{{$errors->first('name')}}</span>@enderror
                            </div>
                        </div>

                        <div class="form-group row no-gutters">
                            <label for="editShopAddress" class="col-sm-2 col-form-label">Address</label>
                            <div class="col-sm-10">
                                <textarea name="address" id="editShopAddress" class="form-control"
                                          placeholder="Enter address (optional)"></textarea>
                            </div>
                        </div>

                        <div class="form-group row no-gutters">
                            <label for="editShopLogo" class="col-sm-2 col-form-label">Logo</label>
                            <div class="col-sm-10">
                                <div class="custom-file">
                                    <input type="file" name="logo_image" class="custom-file-input"
                                           accept="image/jpeg, image/png"
                                           aria-describedby="editShopLogo">
                                    <label class="custom-file-label" for="editShopLogo">Choose logo</label>
                                </div>
                                <img src="" id="editShopLogo" class="img-fluid mt-2"
                                     style="height: 80px; width: 80px;" alt="">
                            </div>
                        </div>

                        <div class="form-group row no-gutters">
                            <label for="editShopStatus" class="col-sm-2 col-form-label">Status</label>
                            <div class="col-sm-10">
                                <input type="checkbox" id="editShopStatus" name="status" data-bootstrap-switch
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

        <div class="modal fade" id="showShopModal" tabindex="-1" aria-labelledby="showShopModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="showShopModalLabel">@lang('trans.show_shop')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-borderless">
                            <tbody>
                            <tr>
                                <th>Name:</th>
                                <td id="showShopName"></td>
                            </tr>
                            <tr>
                                <th>Logo Image:</th>
                                <td>
                                    <img src="" id="showShopLogo" alt="Shop logo"
                                         class="img-fluid img-circle">
                                </td>
                            </tr>
                            <tr>
                                <th>Address:</th>
                                <td id="showShopAddress"></td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td id="showShopStatus"></td>
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
        // all Shops in json format
        let shops = @json($shops);
        let brandImagePath = "{{imagePath()['brand']['path']}}";

        $(document).ready(function () {
            //datatable
            $("#shopTable").DataTable({
                "responsive": true,
                "autoWidth": false,
                "columnDefs": [
                    {"orderable": false, "targets": [3]}
                ],
                "pageLength": {{settings('per_page')}}
            });

            // shop create form
            $('#shopCreateForm').validate({
                rules: {
                    name: {
                        required: true,
                    },
                },
                messages: {
                    name: {
                        required: "Please enter a shop name",
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

            // shop edit form
            $('#shopEditForm').validate({
                rules: {
                    name: {
                        required: true,
                    },
                },
                messages: {
                    name: {
                        required: "Please enter a shop name",
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
        function openShowShopModal(slug) {
            let shop = shops.find(shop => shop.slug == slug);
            // Set update row value
            $('#showShopName').html(shop.name);

            if (shop.logo_image) {
                $('#showShopLogo').attr('src', app_url + '/' + brandImagePath + '/' + shop.logo_image);
            } else {
                $('#showShopLogo').attr('src', app_url + '/' + brandImagePath + '/' + 'default-brand.png');
            }
            $('#showShopAddress').html(shop.address);
            $('#showShopStatus').html('');
            $('#showShopStatus').append(shop.status === 1 ? "<span class='badge badge-success'>Active</span>" : "<span class='badge badge-danger'>Inactive</span>");

            // Open modal
            $('#showShopModal').modal('show');
        }


        function openEditShopModal(slug) {
            let shop = shops.find(shop => shop.slug == slug);

            // Set edit form action url
            $('#shopEditForm').attr('action', app_url + '/admin/shops/' + shop.slug);

            // Set update row value
            $('#editShopName').val(shop.name);
            $('#editShopAddress').html(shop.address);

            if (shop.logo_image) {
                $('#editShopLogo').attr('src', app_url + '/' + brandImagePath + '/' + shop.logo_image);
            } else {
                $('#editShopLogo').attr('src', app_url + '/' + brandImagePath + '/' + 'default-brand.png');
            }

            shop.status == 1 ? $('#editShopStatus').bootstrapSwitch('state', shop.status, true) : $('#editShopStatus').bootstrapSwitch('state', shop.status, false);
            ;

            // Open modal
            $('#editShopModal').modal('show');

        }

        // Delete Shop
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


        // Shop Status Change
        function shopStatusChange(slug) {
            Swal.fire({
                title: 'Are you sure to change?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.value) {
                    window.location.href = $('#shopStatus-' + slug).data('href');
                }
            });
        }
    </script>
@endpush
