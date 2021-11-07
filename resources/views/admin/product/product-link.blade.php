@extends('admin.layouts.app')
@section('title')
    @lang('trans.product_link')
@endsection
@push('style')
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
{{--                    <h1 class="m-0 text-dark">@lang('trans.manage_product_link')</h1>--}}
                    <h1 class="m-0 text-dark">@lang('trans.manage') {{$product->name}} @lang('trans.link')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">@lang('trans.dashboard')</a></li>
                        <li class="breadcrumb-item"><a href="{{route('products.index')}}">@lang('trans.product')</a></li>
                        <li class="breadcrumb-item active">@lang('trans.product_link')</li>
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
                        <h3 class="card-title float-left">@lang('trans.all_product_links')
                        </h3>
                        @can('create-product-link')
                        <button type="button"
                                data-toggle="modal"
                                data-target="#createProductLinkModal"
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
                                <th style="width: 15%">Shop</th>
                                <th style="width: 15%">Product</th>
                                <th style="width: 10%">Price</th>
                                <th style="width: 10%">Shipping Charge</th>
                                <th style="width: 5%">Tax</th>
                                <th style="width: 10%">Stock</th>
                                <th style="width: 10%">Status</th>
                                <th style="width: 20%">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($i = 1)
                            @foreach($productLinks as $productLink)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{@$productLink->shop->name}}</td>
                                    <td>{{@$productLink->product->name}}</td>
                                    <td>{{$productLink->price}}</td>
                                    <td>{{$productLink->shipping}}</td>
                                    <td>{{$productLink->tax}}</td>
                                    <td>{{getProductStock($productLink->stock)}}</td>
                                    <td>
                                        <a href="javascript:void(0)"
                                           @can('status-change-product-link')
                                           onclick="productLinkStatusChange('{{$productLink->id}}')"
                                           @endcan
                                           data-href="{{route('productLinks.status.update', [ $product->slug, $productLink->id])}}"
                                           data-toggle="tooltip"
                                           title="@lang('trans.change_status')"
                                           id="productLinkStatus-{{$productLink->id}}"
                                        >
                                            <span
                                                class="badge {{$productLink->status == 1 ? 'badge-success' : 'badge-danger'}}">
                                            {{$productLink->status == 1 ? 'Active' : 'Inactive' }}
                                        </span>
                                        </a>
                                    </td>
                                    <td>
                                        @can('show-product-link')
                                        <button onclick="openShowProductLinkModal('{{$productLink->id}}')" type="button"
                                                class="btn btn-sm btn-primary" data-toggle="tooltip"
                                                title="@lang('trans.view')"><i class="fa fa-search-plus"></i></button>
                                        @endcan
                                        @can('update-product-link')
                                        <button onclick="openEditProductLinkModal('{{$productLink->id}}')" type="button"
                                                class="btn btn-sm btn-success" data-toggle="tooltip"
                                                title="@lang('trans.edit')"><i class="fa fa-edit"></i></button>
                                            @endcan

                                            @can('delete-product-link')
                                        {{Form::open(['route'=>['productLinks.destroy', $product->slug, $productLink->id], 'method'=>'DELETE', 'id' => "deleteForm-$productLink->id", 'class' => 'd-inline'])}}
                                        <button type="button"
                                                onclick="deleteBtn('{{$productLink->id}}')"
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
        <div class="modal fade" id="createProductLinkModal" tabindex="-1" aria-labelledby="createProductLinkModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createProductLinkModalLabel">@lang('trans.create') {{$product->name}} @lang('trans.link')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    {{Form::open(['route' => ['productLinks.store', $product->slug], 'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'productLinkCreateForm', 'enctype' => 'multipart/form-data'])}}
                    <div class="modal-body">
{{--                        <input type="hidden" name="product_id" value="{{$product->id}}">--}}
                        <div class="form-group row no-gutters">
                            <label for="createShopId" class="col-sm-3 col-form-label mandatory">Shop</label>
                            <div class="col-sm-9">
                                <select
                                    class="form-control select2 select2-info @error('shop_id') is-invalid @enderror"
                                    data-dropdown-css-class="select2-info"
                                    name="shop_id" id="createShopId" style="width: 100%;">
                                    <option value="">Select shop</option>
                                    @foreach ($shops as $shop)
                                        @if(!$existingShops->contains($shop->id))
                                        <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('shop_id')<span class="text-danger">{{$errors->first('shop_id')}}</span>@enderror
                            </div>
                        </div>

                        <div class="form-group row no-gutters">
                            <label for="createPrice" class="col-sm-3 col-form-label mandatory">Price</label>
                            <div class="col-sm-9">
                                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" id="createPrice" placeholder="Enter price">
                                @error('price')<span class="text-danger">{{$errors->first('price')}}</span>@enderror
                            </div>
                        </div>

                        <div class="form-group row no-gutters">
                            <label for="promo" class="col-sm-3 col-form-label">Promo/Coupon</label>
                            <div class="col-sm-9">
                                <input type="text" name="promo" class="form-control"
                                        id="promo" placeholder="Enter promo/coupon code">
                            </div>
                        </div>

                        <div class="form-group row no-gutters">
                            <label for="shipping" class="col-sm-3 col-form-label">Shipping Charge</label>
                            <div class="col-sm-9">
                                <input type="text" name="shipping" class="form-control"
                                       id="shipping" placeholder="Enter shipping charge">
                            </div>
                        </div>

                        <div class="form-group row no-gutters">
                            <label for="tax" class="col-sm-3 col-form-label">Tax</label>
                            <div class="col-sm-9">
                                <input type="number" name="tax" class="form-control" id="tax" placeholder="Enter tax">
                            </div>
                        </div>

                        <div class="form-group row no-gutters">
                            <label for="stock" class="col-sm-3 col-form-label">Stock</label>
                            <div class="col-sm-9">
                                <select class="form-control" name="stock"  id="stock">
                                    <option value="1">In stock</option>
                                    <option value="2">Out of stock</option>
                                    <option value="3">Upcoming</option>
                                    <option value="4">Discontinued</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row no-gutters">
                            <label for="url" class="col-sm-3 col-form-label mandatory">Product URL</label>
                            <div class="col-sm-9">
                                <input type="text" name="url" class="form-control @error('url') is-invalid @enderror"
                                       id="url" placeholder="Enter url">
                                @error('url')<span class="text-danger">{{$errors->first('url')}}</span>@enderror
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


        <div class="modal fade" id="editProductLinkModal" tabindex="-1" aria-labelledby="editProductLinkModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProductLinkModalLabel">@lang('trans.edit') {{$product->name}} @lang('trans.link')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    {{Form::open(['id' => 'productLinkEditForm', 'method' => 'PATCH', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data'])}}
                    <div class="modal-body">

                        <div class="form-group row no-gutters">
                            <label for="editProductLinkShop" class="col-sm-3 col-form-label">Shop</label>
                            <div class="col-sm-9">
                                <select
                                    class="form-control select2 select2-info"
                                    data-dropdown-css-class="select2-info"
                                    name="shop_id" id="editProductLinkShop" style="width: 100%;">
                                </select>
                            </div>
                        </div>

                        <div class="form-group row no-gutters">
                            <label for="editProductLinkPrice" class="col-sm-3 col-form-label">Price</label>
                            <div class="col-sm-9">
                                <input type="number" name="price" class="form-control"
                                       id="editProductLinkPrice" placeholder="Enter price">
                            </div>
                        </div>

                        <div class="form-group row no-gutters">
                            <label for="editProductLinkPromo" class="col-sm-3 col-form-label">Promo/Coupon</label>
                            <div class="col-sm-9">
                                <input type="text" name="promo" class="form-control"
                                       id="editProductLinkPromo" placeholder="Enter promo/coupon">
                            </div>
                        </div>

                        <div class="form-group row no-gutters">
                            <label for="editProductLinkShipping" class="col-sm-3 col-form-label">Shipping Charge</label>
                            <div class="col-sm-9">
                                <input type="text" name="shipping" class="form-control"
                                       id="editProductLinkShipping" placeholder="Enter shipping charge">
                            </div>
                        </div>

                        <div class="form-group row no-gutters">
                            <label for="editProductLinkTax" class="col-sm-3 col-form-label">Tax</label>
                            <div class="col-sm-9">
                                <input type="number" name="tax" class="form-control"
                                       id="editProductLinkTax" placeholder="Enter tax">
                            </div>
                        </div>

                        <div class="form-group row no-gutters">
                            <label for="editProductLinkStock" class="col-sm-3 col-form-label">Stock</label>
                            <div class="col-sm-9">
                                <select class="form-control"  name="stock"  id="editProductLinkStock" >
                                    <option value="1">In stock</option>
                                    <option value="2">Out of stock</option>
                                    <option value="3">Upcoming</option>
                                    <option value="4">Discontinued</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row no-gutters">
                            <label for="editProductLinkUrl" class="col-sm-3 col-form-label">Product URL</label>
                            <div class="col-sm-9">
                                <input type="text" name="url" class="form-control"
                                       id="editProductLinkUrl" placeholder="Enter url">
                            </div>
                        </div>




                        <div class="form-group row no-gutters">
                            <label for="editProductLinkStatus" class="col-sm-3 col-form-label">Status</label>
                            <div class="col-sm-9">
                                <input type="checkbox" id="editProductLinkStatus" name="status" data-bootstrap-switch
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


        <div class="modal fade" id="showProductLinkModal" tabindex="-1" aria-labelledby="showProductLinkModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="showProductLinkModalLabel">@lang('trans.show') {{$product->name}} @lang('trans.link')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-borderless">
                            <tbody>
                            <tr>
                                <th>Shop:</th>
                                <td id="showProductLinkShopName"></td>
                            </tr>
                            <tr>
                                <th>Product:</th>
                                <td id="showProductLinkProductName"></td>
                            </tr>
                            <tr>
                                <th>Price:</th>
                                <td id="showProductLinkPrice"></td>
                            </tr>
                            <tr>
                                <th>Promo/Coupon:</th>
                                <td id="showProductLinkPromo"></td>
                            </tr>
                            <tr>
                                <th>Shipping Charge:</th>
                                <td id="showProductLinkShipping"></td>
                            </tr>
                            <tr>
                                <th>Tax:</th>
                                <td id="showProductLinkTax"></td>
                            </tr>
                            <tr>
                                <th>Stock:</th>
                                <td id="showProductLinkStock"></td>
                            </tr>
                            <tr>
                                <th>Product URL:</th>
                                <td id="showProductLinkUrl"></td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td id="showProductLinkStatus"></td>
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
        // all Products in json format
        let productLinks = @json($productLinks);
        let shops = @json($shops);
        let existingShops = @json($existingShops);

        {{--let categories = @json($categories);--}}
        {{--let tags = @json($tags);--}}
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


            // console.log(existingShops);
            // $("#createShopId").empty();
            // $.each(shops, function (index, shop) {
            //     // Check shop is used in another product link
            //     if(jQuery.inArray(shop.id, existingShops) == -1){
            //         $('#createShopId').append(`<option value="` + shop.id + `">` + shop.name + `</option>`);
            //     }
            // });



            // product create form
            $('#productLinkCreateForm').validate({
                rules: {
                    shop_id: {
                        required: true,
                    },
                    price: {
                        required: true,
                    },
                    url: {
                        required: true,
                    },
                },
                messages: {
                    shop_id: {
                        required: "The shop field is required.",
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

            // product edit form
            $('#productLinkEditForm').validate({
                rules: {
                    shop_id: {
                        required: true,
                    },
                    price: {
                        required: true,
                    },
                    url: {
                        required: true,
                    },
                },
                messages: {
                    shop_id: {
                        required: "The shop field is required.",
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
        function openShowProductLinkModal(id) {
            let productLink = productLinks.find(productLink => productLink.id == id);
            // Set update row value
            $('#showProductLinkShopName').html(productLink.shop.name);
            $('#showProductLinkProductName').html(productLink.product.name);
            $('#showProductLinkPrice').html(productLink.price);
            $('#showProductLinkPromo').html(productLink.promo);
            $('#showProductLinkShipping').html(productLink.shipping);
            $('#showProductLinkTax').html(productLink.tax);
            $('#showProductLinkUrl').html(productLink.url);
            $('#showProductLinkStock').html(getProductStockJquery(productLink.stock));
            // $('#showProductLinkStatus').html(productLink.status);


            $('#showProductLinkStatus').html('');
            $('#showProductLinkStatus').append(productLink.status === 1 ? "<span class='badge badge-success'>Active</span>" : "<span class='badge badge-danger'>Inactive</span>");

            // Open modal
            $('#showProductLinkModal').modal('show');
        }

        function openEditProductLinkModal(id) {
            let productLink = productLinks.find(productLink => productLink.id == id);

            // Set edit form action url
            $('#productLinkEditForm').attr('action', app_url + '/admin/products/'+productLink.product.slug+'/productLinks/' + productLink.id);

            // Set update row value
            $('#editProductLinkPrice').val(productLink.price);
            $('#editProductLinkPromo').val(productLink.promo);
            $('#editProductLinkShipping').val(productLink.shipping);
            $('#editProductLinkTax').val(productLink.tax);
            $('#editProductLinkUrl').val(productLink.url);

            $('#editProductLinkStock option[value=' + productLink.stock + ']').attr("selected", "selected");

            // console.log(productLink)
            $("#editProductLinkShop").empty();
            $.each(shops, function (index, shop) {
                // Check shop is used in another product link
                if($.inArray(shop.id, existingShops) == -1 || shop.id == productLink.shop_id){
                    $('#editProductLinkShop').append(`<option value="` + shop.id + `">` + shop.name + `</option>`)
                }
            });

            $('#editProductLinkShop option[value=' + productLink.shop_id + ']').attr("selected", "selected");



            productLink.status == 1 ? $('#editProductLinkStatus').bootstrapSwitch('state', productLink.status, true) : $('#editProductLinkStatus').bootstrapSwitch('state', productLink.status, false);


            // Open modal
            $('#editProductLinkModal').modal('show');

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
        function productLinkStatusChange(slug) {
            Swal.fire({
                title: 'Are you sure to change?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.value) {
                    window.location.href = $('#productLinkStatus-' + slug).data('href');
                }
            });
        }


    </script>
@endpush

