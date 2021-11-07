@extends('admin.layouts.app')
@section('title')
    @lang('trans.product')
@endsection
@push('style')
    <!-- summernote -->
{{--    <link rel="stylesheet" href="{{asset('assets/admin/plugins/summernote/summernote-bs4.css')}}">--}}
    <style>
        .product-image {
            height: 58px;
            width: 70px;
        }

        .show-image {
            height: 100px;
            width: 120px;
        }
    </style>
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row no-gutters mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">@lang('trans.manage_product')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">@lang('trans.dashboard')</a></li>
                        <li class="breadcrumb-item active">@lang('trans.product')</li>
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
                        <h3 class="card-title float-left">@lang('trans.all_products')
                        </h3>
                        @can('create-product')
                        <button type="button"
                                data-toggle="modal"
                                data-target="#createProductModal"
                                class="btn btn-info float-right btn-sm">
                            <i class="fa fa-plus"></i> @lang('trans.add_new')
                        </button>
                        @endcan
                    </div>
                    <div class="card-body">
                        <table id="productTable" class="table text-center table-bordered ">
                            <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th style="width: 20%">Name</th>
{{--                                <th style="width: 10%">Sort description</th>--}}
                                <th style="width: 15%">Brand</th>
                                <th style="width: 15%">Image</th>
                                <th style="width: 15%">Category</th>
                                <th style="width: 10%">Status</th>
                                <th style="width: 20%">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($i = 1)
                            @foreach($products as $product)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>
                                        @can('access-product-link')
                                        <a href="{{route('products.edit', $product->slug)}}" class="list-style-none" >{{$product->name}}</a>
                                        @else
                                            {{$product->name}}
                                        @endcan
                                    </td>
{{--                                    <td>{{$product->sort_description}}</td>--}}
                                    <td>{{$product->brand->name}}</td>
                                    <td>
                                        <img
                                            src="{{getImage(imagePath()['product']['path'] . '/' . $product->base_image, imagePath()['product']['size'])}}"
                                            alt="Product image" class="product-image">
                                    </td>
                                    <td>
                                        @foreach($product->categories as $category)
                                            <span class="badge badge-info"> {{$category->name}}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)"
                                           @can('status-change-product')
                                           onclick="productStatusChange('{{$product->slug}}')"
                                           @endcan
                                           data-href="{{route('products.status.update', $product->slug)}}"
                                           data-toggle="tooltip"
                                           title="@lang('trans.change_status')"
                                           id="productStatus-{{$product->slug}}"
                                        >
                                            <span
                                                class="badge {{$product->status == 1 ? 'badge-success' : 'badge-danger'}}">
                                            {{$product->status == 1 ? 'Active' : 'Inactive' }}
                                        </span>
                                        </a>
                                    </td>
                                    <td>
                                       @can('show-product')
                                        <button onclick="openShowProductModal('{{$product->slug}}')" type="button"
                                                class="btn btn-sm btn-primary" data-toggle="tooltip"
                                                title="@lang('trans.view')"><i class="fa fa-search-plus"></i></button>
                                        @endcan
                                        @can('update-product')
                                        <button onclick="openEditProductModal('{{$product->slug}}')" type="button"
                                                class="btn btn-sm btn-success" data-toggle="tooltip"
                                                title="@lang('trans.edit')"><i class="fa fa-edit"></i></button>
                                           @endcan

                                           @can('delete-product')
                                        {{Form::open(['route'=>['products.destroy', $product->slug], 'method'=>'DELETE', 'id' => "deleteForm-$product->slug", 'class' => 'd-inline'])}}
                                        <button type="button"
                                                @can('access-product')
                                                onclick="deleteBtn('{{$product->slug}}')"
                                                @endcan
                                                class="btn btn-sm btn-danger" data-toggle="tooltip"
                                                title="@lang('trans.delete')"><i class="fa fa-trash"></i></button>
                                        {{Form::close()}}
                                           @endcan
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
        <div class="modal fade" id="createProductModal" tabindex="-1" aria-labelledby="createProductModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createProductModalLabel">@lang('trans.create_product')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    {{Form::open(['route' => 'products.store', 'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'productCreateForm', 'enctype' => 'multipart/form-data'])}}
                    <div class="modal-body">
                        <div class="form-group row no-gutters">
                            <label for="name" class="col-sm-3 col-form-label mandatory">Name</label>
                            <div class="col-sm-9">
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{old('name')}}" id="name" placeholder="Enter product name">
                                @error('name')<span class="text-danger">{{$errors->first('name')}}</span>@enderror

                            </div>
                        </div>

                        <div class="form-group row no-gutters">
                            <label for="sort_description" class="col-sm-3 col-form-label">Sort Description</label>
                            <div class="col-sm-9">
                                <textarea name="sort_description" id="sort_description" class="form-control"
                                          placeholder="Enter sort description (optional)"></textarea>
                            </div>
                        </div>

                        <div class="form-group row no-gutters">
                            <label for="long_description" class="col-sm-3 col-form-label">Long Description</label>
                            <div class="col-sm-9">
                                <textarea name="long_description" id="long_description" class="form-control textarea"
                                          placeholder="Enter long description (optional)"></textarea>
                            </div>
                        </div>

                        <div class="form-group row no-gutters">
                            <label for="brand_id" class="col-sm-3 col-form-label mandatory">Brand</label>
                            <div class="col-sm-9">
                                <select
                                    class="form-control select2 select2-info @error('brand_id') is-invalid @enderror"
                                    data-dropdown-css-class="select2-info"
                                    name="brand_id" id="brand_id" style="width: 100%;">
                                    <option value="">Select brand</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                                @error('brand_id')<span class="text-danger">{{$errors->first('brand_id')}}</span>@enderror
                            </div>
                        </div>


                        <div class="form-group row no-gutters">
                            <label for="base_image" class="col-sm-3 col-form-label mandatory"
                                   id="inputGroupFileAddon05">Base image</label>
                            <div class="col-sm-9">
                                <div class="custom-file">
                                    <input type="file" name="base_image"
                                           class="custom-file-input @error('name') base_image @enderror"
                                           accept="image/jpeg, image/png" id="base_image"
                                           aria-describedby="inputGroupFileAddon05">
                                    <label class="custom-file-label" for="base_image">Choose product image</label>
                                </div>
                                @error('base_image')<span
                                    class="text-danger">{{$errors->first('base_image')}}</span>@enderror
                            </div>
                        </div>


                        <div class="form-group row no-gutters">
                            <label for="category_id" class="col-sm-3 col-form-label">Category</label>
                            <div class="col-sm-9">
                                <select
                                    class="form-control select2 select2-info"
                                    multiple="multiple"
                                    data-dropdown-css-class="select2-info"
                                    data-placeholder="Select category"
                                    name="category_id[]" id="category_id" style="width: 100%;">
                                    <option value="">Select category (optional)</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row no-gutters">
                            <label for="tag_id" class="col-sm-3 col-form-label">Tag</label>
                            <div class="col-sm-9">
                                <select
                                    class="form-control select2 select2-info"
                                    multiple="multiple"
                                    data-dropdown-css-class="select2-info"
                                    data-placeholder="Select tag"
                                    name="tag_id[]" id="tag_id" style="width: 100%;">
                                    <option value="">Select tag (optional)</option>
                                    @foreach ($tags as $tag)
                                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="form-group row no-gutters">
                            <label for="images" class="col-sm-3 col-form-label"
                                   id="inputGroupFileAddon05">Images</label>
                            <div class="col-sm-9">
                                <div class="custom-file">
                                    <input type="file" name="images[]"
                                           multiple
                                           class="custom-file-input @error('name') images @enderror"
                                           accept="image/jpeg, image/png" id="images"
                                           aria-describedby="inputGroupFileAddon05">
                                    <label class="custom-file-label" for="images">Choose product image</label>
                                </div>
                                @error('images')<span
                                    class="text-danger">{{$errors->first('images')}}</span>@enderror
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
                            <button type="submit" class="btn btn-sm btn-info">Create</button>
                            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                        {{Form::close()}}
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProductModalLabel">@lang('trans.edit_product')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    {{Form::open(['id' => 'productEditForm', 'method' => 'PATCH', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data'])}}
                    <div class="modal-body">
                        <div class="form-group row no-gutters">
                            <label for="editProductName" class="col-sm-3 col-form-label">Name</label>
                            <div class="col-sm-9">
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       id="editProductName" placeholder="Enter shop name">
                                @error('name')<span class="text-danger">{{$errors->first('name')}}</span>@enderror
                            </div>
                        </div>

                        <div class="form-group row no-gutters">
                            <label for="editProductSortDescription" class="col-sm-3 col-form-label">Sort Description</label>
                            <div class="col-sm-9">
                                <textarea name="sort_description" id="editProductSortDescription" class="form-control"
                                          placeholder="Enter sort description (optional)"></textarea>
                            </div>
                        </div>

                        <div class="form-group row no-gutters">
                            <label for="editProductLongDescription" class="col-sm-3 col-form-label">Long Description</label>
                            <div class="col-sm-9">
                                <textarea name="long_description" id="editProductLongDescription" class="form-control textarea"
                                          placeholder="Enter long description (optional)"></textarea>
                            </div>
                        </div>


                        <div class="form-group row no-gutters">
                            <label for="editProductBrand" class="col-sm-3 col-form-label">Brand</label>
                            <div class="col-sm-9">
                                <select
                                    class="form-control select2 select2-info"
                                    data-dropdown-css-class="select2-info"
                                    name="brand_id" id="editProductBrand" style="width: 100%;">
                                </select>
                            </div>
                        </div>

                        <div class="form-group row no-gutters">
                            <label for="editProductCategory" class="col-sm-3 col-form-label">Category</label>
                            <div class="col-sm-9">
                                <select
                                    class="form-control select2 select2-info"
                                    multiple="multiple"
                                    data-dropdown-css-class="select2-info"
                                    data-placeholder="Select category"
                                    name="category_id[]" id="editProductCategory" style="width: 100%;">
                                </select>
                            </div>
                        </div>

                        <div class="form-group row no-gutters">
                            <label for="editProductTag" class="col-sm-3 col-form-label">Tag</label>
                            <div class="col-sm-9">
                                <select
                                    class="form-control select2 select2-info"
                                    multiple="multiple"
                                    data-dropdown-css-class="select2-info"
                                    data-placeholder="Select tag"
                                    name="tag_id[]" id="editProductTag" style="width: 100%;">
                                </select>
                            </div>
                        </div>



{{--                        <div class="form-group row no-gutters">--}}
{{--                            <label for="editProductCategory" class="col-sm-3 col-form-label">Category</label>--}}
{{--                            <div class="col-sm-9">--}}
{{--                                <select--}}
{{--                                    class="form-control select2 select2-info"--}}
{{--                                    data-dropdown-css-class="select2-info"--}}
{{--                                    name="category_id" id="editProductCategory" style="width: 100%;">--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}


                        <div class="form-group row no-gutters">
                            <label for="editProductImage" class="col-sm-3 col-form-label">Base image</label>
                            <div class="col-sm-9">
                                <div class="custom-file">
                                    <input type="file" name="base_image" class="custom-file-input"
                                           accept="image/jpeg, image/png"
                                           aria-describedby="editProductImage">
                                    <label class="custom-file-label" for="editProductImage">Choose product image</label>
                                </div>
                                <img src="" id="editProductImage" class="img-fluid mt-2"
                                     style="height: 80px; width: 80px;" alt="">
                            </div>
                        </div>


                        <div class="form-group row no-gutters">
                            <label for="editShowImages" class="col-sm-3 col-form-label"
                                   >Images</label>
                            <div class="col-sm-9">
                                <div class="custom-file">
                                    <input type="file" name="images[]"
                                           multiple
                                           class="custom-file-input"
                                           accept="image/jpeg, image/png" id="editImages">
                                    <label class="custom-file-label" for="editShowImages">Choose product image</label>
                                </div>
{{--                                <img src="" id="editImages" class="img-fluid mt-2"--}}
{{--                                     style="height: 80px; width: 80px;" alt="">--}}
                                <div id="editShowImages"></div>
                            </div>
                        </div>


                        <div class="form-group row no-gutters">
                            <label for="editProductStatus" class="col-sm-3 col-form-label">Status</label>
                            <div class="col-sm-9">
                                <input type="checkbox" id="editProductStatus" name="status" data-bootstrap-switch
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


        <div class="modal fade" id="showProductModal" tabindex="-1" aria-labelledby="showProductModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="showProductModalLabel">@lang('trans.show_product')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-borderless">
                            <tbody>
                            <tr>
                                <th>Name:</th>
                                <td id="showProductName"></td>
                            </tr>
                            <tr>
                                <th>Sort Description:</th>
                                <td id="showProductSortDescription"></td>
                            </tr>
                            <tr>
                                <th>Long Description:</th>
                                <td id="showProductLongDescription"></td>
                            </tr>
                            <tr>
                                <th>Brand:</th>
                                <td id="showProductBrand"></td>
                            </tr>
                            <tr>
                                <th>Base Image:</th>
                                <td>
                                    <img src="" id="showProductImage" alt="Product image"
                                         class="img-fluid show-image">
                                </td>
                            </tr>
                            <tr>
                                <th>Category:</th>
                                <td id="showProductCategory"></td>
                            </tr>
                            <tr>
                                <th>Tag:</th>
                                <td id="showProductTag"></td>
                            </tr>
                            <tr>
                                <th>Images:</th>
                                <td id="showImages"></td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td id="showProductStatus"></td>
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
    <!-- Summernote -->
{{--    <script src="{{asset('assets/admin/plugins/summernote/summernote-bs4.min.js')}}"></script>--}}

    <script>
        // all Products in json format
        let products = @json($products);
        let brands = @json($brands);
        let categories = @json($categories);
        let tags = @json($tags);
        let productImagePath = "{{imagePath()['product']['path']}}";

        $(document).ready(function () {
            //datatable
            $("#productTable").DataTable({
                "responsive": true,
                "autoWidth": false,
                "columnDefs": [
                    {"orderable": false, "targets": [3]}
                ],
                "pageLength": {{settings('per_page')}}
            });

            // select2
            $('.select2').select2();

            // Summernote
            // $('.textarea').summernote({
            //     height: 200,
            // })

            // product create form
            $('#productCreateForm').validate({
                rules: {
                    name: {
                        required: true,
                    },
                    // brand_id: {
                    //     required: true,
                    // },
                },
                messages: {
                    name: {
                        required: "Please enter a product name",
                    },
                    // brand_id: {
                    //     required: 'The brand field is required.',
                    // },
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

            // product edit form
            $('#productEditForm').validate({
                rules: {
                    name: {
                        required: true,
                    },
                },
                messages: {
                    name: {
                        required: "Please enter a product name",
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
        function openShowProductModal(slug) {
            let product = products.find(product => product.slug == slug);
            // Set update row value
            $('#showProductName').html(product.name);
            $('#showProductSortDescription').html(product.sort_description);
            // $('#showProductLongDescription').html(product.long_description);
            $('#showProductLongDescription').append(product.long_description);
            $('#showProductBrand').html(product.brand.name);

            if (product.base_image) {
                $('#showProductImage').attr('src', app_url + '/' + productImagePath + '/' + product.base_image);
            } else {
                $('#showProductImage').attr('src', app_url + '/' + productImagePath + '/' + 'default-product.png');
            }

            $('#showProductCategory').html('');
            if (product.categories) {
                $.each(product.categories, function (index, category) {
                    // $('#showProductCategory').append(`<span class="badge badge-info mr-1">` + category.name + `</span>`)
                    $('#showProductCategory').append("<span class='badge badge-info mr-1badge badge-info mr-1'>" + category.name + "</span>");

                });
            }

            $('#showProductTag').html('');
            if (product.tags) {
                $.each(product.tags, function (index, tag) {
                    $('#showProductTag').append(`<span class="badge badge-info mr-1">` + tag.name + `</span>`)

                });
            }

            $('#showImages').html('');
            if (product.product_images) {
                $.each(product.product_images, function (index, productImage) {

                    $('#showImages').append(`<img src="` + app_url + '/' + productImagePath + '/' + productImage.name + `" alt="Product image" class="mr-1 img-fluid show-image">`)

                });
            }


            $('#showProductStatus').html('');
            $('#showProductStatus').append(product.status === 1 ? "<span class='badge badge-success'>Active</span>" : "<span class='badge badge-danger'>Inactive</span>");

            // Open modal
            $('#showProductModal').modal('show');
        }

        function openEditProductModal(slug) {
            let product = products.find(product => product.slug == slug);

            // Set edit form action url
            $('#productEditForm').attr('action', app_url + '/admin/products/' + product.slug);

            // Set update row value
            $('#editProductName').val(product.name);
            $('#editProductSortDescription').val(product.sort_description);
            $('#editProductLongDescription').val(product.long_description);
            // $('#editProductLongDescription').summernote('code', product.long_description);

            // console.log(product)
            $("#editProductBrand").empty();
            $.each(brands, function (index, brand) {
                $('#editProductBrand').append(`<option value="` + brand.id + `">` + brand.name + `</option>`)
            });
            $('#editProductBrand option[value=' + product.brand_id + ']').attr("selected", "selected");

            // console.log(product)
            $("#editProductCategory").empty();
            $.each(categories, function (index, category) {
                $('#editProductCategory').append(`<option value="` + category.id + `">` + category.name + `</option>`)
            });

            $.each(product.categories, function (index, category) {
                $('#editProductCategory option[value=' + category.id + ']').attr("selected", "selected");
            });



            $("#editProductTag").empty();
            $.each(tags, function (index, tag) {
                $('#editProductTag').append(`<option value="` + tag.id + `">` + tag.name + `</option>`)
            });

            $.each(product.tags, function (index, tag) {
                $('#editProductTag option[value=' + tag.id + ']').attr("selected", "selected");
            });



            if (product.base_image) {
                $('#editProductImage').attr('src', app_url + '/' + productImagePath + '/' + product.base_image);
            } else {
                $('#editProductImage').attr('src', app_url + '/' + productImagePath + '/' + 'default-product.png');
            }


            $('#editShowImages').html('');
            if (product.product_images) {
                $.each(product.product_images, function (index, productImage) {

                    $('#editShowImages').append(`<img src="` + app_url + '/' + productImagePath + '/' + productImage.name + `" alt="Product image" class="mr-1 mt-2 img-fluid show-image">`)

                });
            }


            product.status == 1 ? $('#editProductStatus').bootstrapSwitch('state', product.status, true) : $('#editProductStatus').bootstrapSwitch('state', product.status, false);
            ;

            // Open modal
            $('#editProductModal').modal('show');

        }

        // Delete Product
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

        // Product Status Change
        function productStatusChange(slug) {
            Swal.fire({
                title: 'Are you sure to change?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.value) {
                    window.location.href = $('#productStatus-' + slug).data('href');
                }
            });
        }
    </script>
@endpush
