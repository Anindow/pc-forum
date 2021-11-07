@extends('frontend.layouts.app')

@section('title', 'Product List' . ' |')

@push('style')
    <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-slider/css/bootstrap-slider.min.css')}}">
    <style>
        .custom-size {
            font-size: 8px !important;
        }
    </style>
@endpush
@section('content')
    <section class="py-5" id="main-product">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="text-center mb-3">
                        @isset($category)
                        <h2 class="mb-4">Choose A {{$category->name}}</h2>
                        @elseif($q)
                            <h2 class=" mb-4">Search result of "{{$q}}"</h2>
                        @endisset
                    </div>
                </div>
            </div>

            <hr>

            <div class="row mt-4">
                <aside class="col-md-3 product-sidebar">

                    <div class="my-4">
                        <h5>Price Range</h5>
                        <form>
                            <div class="form-group pr-4">
                                <input type="range" id="rangeSlider" name="range_value" class="form-control-range range-slide" data-slider-min="500" data-slider-max="100000" data-slider-step="5" data-slider-value="[500, 100000]">
                            </div>
                        </form>
                        <div class="input-group pr-4">
                            <input type="text" id="minRange" value="500" class="form-control range-slide">
                            <input type="text" id="maxRange" value="100000" class="form-control range-slide">
                        </div>
                    </div>
                    <hr>
                    <div class="my-4">
                        <h5 class="mb-3">Brand</h5>
                        @foreach($brands as $brand)
                            <div class="custom-control custom-checkbox mb-2">
                                <input type="checkbox" name="brand" value="{{$brand->slug}}"
                                       class="custom-control-input brand" id="{{$brand->slug}}">
                                <label class="custom-control-label" for="{{$brand->slug}}">{{$brand->name}}</label>
                            </div>
                        @endforeach

                    </div>

                    <hr>
                    <div class="my-4">
                        <h5 class="mb-3">Tag</h5>
                        @foreach($tags as $tag)
                            <div class="custom-control custom-checkbox mb-2">
                                <input type="checkbox" name="tag" class="custom-control-input tag"
                                       value="{{$tag->slug}}" id="{{$tag->slug}}">
                                <label class="custom-control-label" for="{{$tag->slug}}">{{$tag->name}}</label>
                            </div>
                        @endforeach


                    </div>

                </aside>


                <div class="col-md-9">

                    <h5>Products</h5>
                    <table class="table table-hover" id="productTable">
                        <thead>
                        <tr>
                            <th style="width: 40%;">Name</th>
                            <th style="width: 20%;">Brand</th>
                            <th style="width: 15%;">Rating</th>
                            <th style="width: 25%;">Price <small>(min-max)</small></th>
                        </tr>
                        </thead>
                        <tbody id="productListTbody">
                        @foreach($products as $product)
                            <tr>
                                <td>
                                    <div class="media">
                                        <img
                                            src="{{getImage(imagePath()['product']['path'] . '/' . $product->base_image, imagePath()['product']['size'])}}"
                                            class="mr-2" height="50" width="50" alt="Product image">
                                        <div class="media-body">
                                            <a href="{{route('product.detail', $product->slug)}}"
                                               class="text-decoration-none text-dark">{{$product->name}}</a>
                                        </div>
                                    </div>


                                </td>
                                <td>{{$product->brand->name}}</td>
                                <td>
                                    @foreach(range(1,5) as $i)
                                        @if($i > $product->avg_rating)
                                            <i class="fa fa-star custom-size"></i>
                                        @else
                                            <i class="fa fa-star custom-size text-success"></i>
                                        @endif
                                    @endforeach
                                   ({{$product->review_count}})
                                </td>
                                <td>
                                    {{'$ '.$product->min_price}} - {{$product->max_price }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>


        </div>
    </section>
@endsection
@push('script')
    <!-- Bootstrap Slider -->
    <script src="{{asset('assets/plugins/bootstrap-slider/bootstrap-slider.min.js')}}"></script>
    <!-- Lodash -->
    <script src="{{asset('assets/plugins/lodash/lodash.min.js')}}"></script>

    {{-- Loading overlay plugin --}}
    <script src="{{asset('assets/admin/plugins/jquery-loading-overlay/loadingoverlay.min.js')}}"></script>
    <script>
        let productImagePath = "{{imagePath()['product']['path']}}";
        let draw = 0;

        $(document).ready(function () {

            // if (params) {
            //     getParams(params)
            // }

            // Instantiate a slider
            slider = $("#rangeSlider").slider({});
            slider.on("slide",function(slideEvt) {
                $('#minRange').val(slideEvt.value[0]);
                $('#maxRange').val(slideEvt.value[1]);
            });
            // slider.on("slide", _.debounce(function(slideEvt) {
            //         $('#minRange').val(slideEvt.value[0]);
            //         $('#maxRange').val(slideEvt.value[1]);
            //         filterData();
            // }, 600));


            $('.range-slide').change(_.debounce(function() {
                filterData();
            }, 600));

            $('[name=brand]').change(_.debounce(function() {
                filterData();
            }, 600));


            $('[name=tag]').change(_.debounce(function() {
                filterData();
            }, 600));

        });

        function filterData() {
            let brands = getFilter('brand');
            let tags = getFilter('tag');

            // loader on
            $("#productTable").LoadingOverlay("show");
            $.ajax({
                type: 'GET',
                url: '/api/products',
                data: {
                    price_range: {min: $('#minRange').val(), max: $('#maxRange').val()},
                    brand: brands,
                    tag: tags,
                    category: "{{isset($category->slug) ? $category->slug : ''}}",
                    draw: draw++,
                }
            }).done(function (res) {
                draw = res.draw;
                // console.log(res)
                $('#productListTbody').html('')
                // loader off
                $("#productTable").LoadingOverlay("hide", true);

                if (res.products.length != 0) {
                    $.each(res.products, function (index, product) {
                        $('#productListTbody').append(`<tr><td><div class="media"><img src="` + app_url + '/' + productImagePath + '/' + product.base_image + `" class="mr-2" height="50" width="50" alt="Product image"><div class="media-body"><a href="` + app_url + '/products/' + product.slug + `" class="text-decoration-none text-dark">` + product.name + `</a></div></div></td><td>` + product.brand.name + `</td><td id="reviewStar-` + product.slug + `"></td><td>` + '$ ' + product.min_price + `-` + product.max_price + `</td></tr>`);

                        showReviewStar(product);

                        });

                }

            }).fail(function (err) {
                // loader off
                $("#productTable").LoadingOverlay("hide", true);
            });
        }

        function getFilter(class_name){
            var filter = [];
            $('.' + class_name + ':checked').each(function (){
                filter.push($(this).val())
            });

            return filter;
        }

        function showReviewStar(rating) {
            for (i = 1; i <= 5; i++) {
                if (i > rating) {
                    return `<i class="fa fa-star custom-size"></i>`;
                    //  $(this).append('<i class="fa fa-star custom-size"></i>');
                } else {
                    return `<i class="fa fa-star text-success custom-size"></i>`;
                    //  $(this).append('<i class="fa fa-star text-success custom-size"></i>');

                }
            }
        }

        function showReviewStar(product) {
            // console.log(product);
            for (i = 1; i <= 5; i++) {
                if (i > product.avg_rating) {
                    $('#reviewStar-' + product.slug).append('<i class="fa fa-star custom-size"></i>');
                } else {
                    $('#reviewStar-' + product.slug).append('<i class="fa fa-star text-success custom-size"></i>');

                }
            }

            $('#reviewStar-' + product.slug).append(` (`+product.review_count+`)`);
        }

        // function getParams(url) {
        //
        //     var queryString = url.substring(url.indexOf('?') + 1);
        //     var paramsArr = queryString.split('&');
        //     var params = [];
        //
        //     for (var i = 0, len = paramsArr.length; i < len; i++) {
        //         var keyValuePair = paramsArr[i].split('=');
        //         params.push({
        //             name: keyValuePair[0],
        //             value: keyValuePair[1]
        //         });
        //
        //         var allPa = keyValuePair[1].split(",");
        //         for (var k = 0; k < allPa.length; k++) {
        //             // console.log(allPa[k])
        //             if (allPa[k]) {
        //
        //                 $('#' + allPa[k]).prop("checked", true);
        //             }
        //         }
        //
        //     }
        //
        //     return params;
        // }
    </script>

@endpush
