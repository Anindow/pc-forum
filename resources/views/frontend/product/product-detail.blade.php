@extends('frontend.layouts.app')

@section('title', 'Product Details' . ' |')

@push('style')
    <!-- Lightbox -->
    <link rel="stylesheet" href="{{asset('assets/plugins/lightbox/css/lightbox.min.css')}}">
@endpush
@section('content')
    <section class="py-5" id="product-details">
        <div class="container details-body">

            <h2 class="text-center">{{$product->name}}</h2>
            <p class="text-center">
                @foreach(range(1,5) as $i)
                    @if($i > $product->avg_rating)
                        <i class="fa fa-star"></i>
                    @else
                        <i class="fa fa-star text-success"></i>
                    @endif
                @endforeach
            </p>
{{--                <p class="text-center">--}}
{{--                    <i class="fa fa-star text-success"></i>--}}
{{--                    <i class="fa fa-star text-success"></i>--}}
{{--                    <i class="fa fa-star text-success"></i>--}}
{{--                    <i class="fa fa-star text-success"></i>--}}
{{--                    <i class="fa fa-star text-success"></i>--}}
{{--                </p>--}}
                <hr>
                <div class="row mt-4">
                    <aside class="col-md-4 product-sidebar">

                        <div class="border border-light p-5">
                            <a href="{{getImage(imagePath()['product']['path'] . '/' . $product->base_image, imagePath()['product']['size'])}}"
                               data-lightbox="p-image" data-title="{{$product->name}}">
                                <img
                                    src="{{getImage(imagePath()['product']['path'] . '/' . $product->base_image, imagePath()['product']['size'])}}"
                                    alt="" height="300" width="300" class="img-fluid d-block p-3">
                            </a>
                        </div>

                        <div class="row d-flex justify-content-around py-3">
                            @foreach($product->productImages as  $productImage)
                                <div class="col-3">
                                    <a href="{{getImage(imagePath()['product']['path'] . '/' . $productImage->name, imagePath()['product']['size'])}}"
                                       data-lightbox="p-image" data-title="{{$product->name}}">
                                        <img
                                            src="{{getImage(imagePath()['product']['path'] . '/' . $productImage->name, imagePath()['product']['size'])}}"
                                            alt="Product image" class="img-fluid">
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <h5>Features</h5>
                        <hr>
                        <ul class="mb-4 list-unstyled">
                            <li class="mb-2"><strong class="mr-5">Brand:</strong> {{$product->brand->name}}</li>
                            <li class="mb-2"><strong class="mr-5">Product Id:</strong> {{$product->id}}</li>
                        </ul>
                        {!! $product->long_description !!}
                    </aside>


                    <div class="col-md-8 ">
                        <h5>Prices</h5>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th scope="col">Shop</th>
                                <th scope="col">Base</th>
                                <th scope="col">Shipping</th>
                                <th scope="col">Availability</th>
                                <th scope="col">Total</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($product->productLinks as $productLink)
                                <tr>
                                    <td>
                                        <img
                                            src="{{getImage(imagePath()['brand']['path'] . '/' . $productLink->shop->logo_image, imagePath()['brand']['size'])}}"
                                            height="50" width="50" alt="">
                                    </td>
                                    <td>{{$productLink->price}}</td>
                                    <td>{{$productLink->shipping}}</td>
                                    <td>{{getProductStock($productLink->stock)}}</td>
                                    <td>{{$productLink->price}}</td>
                                    <td><a href="{{$productLink->url}}" class="btn btn-success btn-sm" target="_blank">Buy</a>
                                    </td>
                                </tr>
                            @endforeach


                            </tbody>
                        </table>

                        <div class="py-3">
                            {!! $product->sort_description !!}
                        </div>


                        <div class="py-3 price-alert">
                            <h5>Price Alert</h5>
                            <hr>
                            <form class="form-inline m-0 p-0" action="{{route('alerts.store', $product->slug)}}"
                                  method="POST">
                                @csrf
                                <div class="form-group row no-gutters">
                                    <label for="" class="col-sm-7 col-form-label">Send an e-mail alert if the price
                                        drops to: $</label>
                                    <div class="col-sm-5">
                                        <div class="input-group ">
                                            <input type="text" name="alert_price"
                                                   class="form-control @error('alert_price') is-invalid @enderror">
                                            <div class="input-group-append">
                                                @guest
                                                    <a href="{{route('login')}}"
                                                       class="btn btn-outline-secondary">Set</a>
                                                @else
                                                    <button type="submit" class="btn btn-outline-secondary">Set</button>
                                                @endguest
                                            </div>
                                        </div>
                                        @error('alert_price')<span class="text-danger">{{$errors->first('alert_price')}}</span>@enderror
                                    </div>
                            </form>
                        </div>


                        <div class="py-3">
                            <h5>Reviews</h5>
                            <hr>
                            @guest
                                <div class="pb-2">
                                    <a href="{{route('login')}}" class="text-decoration-none">login</a> or <a
                                        href="{{route('register')}}" class="text-decoration-none">register</a> to write
                                    your review
                                </div>
                            @else
                                <form action="{{route('reviews.store', $product->slug)}}" method="POST">
                                    @csrf
                                    <div class="row my-4">
                                        <div class="col-sm-6">
                                        <textarea name="description"
                                                  class="form-control @error('description') is-invalid @enderror"
                                                  id="description"
                                                  placeholder="Your Review" rows="3"></textarea>
                                            @error('description')<span
                                                class="text-danger">{{$errors->first('description')}}</span>@enderror
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-inline">
                                                <label for="rating" class="col-sm-2 col-form-label">Rating: </label>
                                                <div class="col-sm-10">
                                                    <span>Bad</span>
                                                    <input class="form-check-input" type="radio" name="rating"
                                                           value="1">
                                                    <input class="form-check-input" type="radio" name="rating"
                                                           value="2">
                                                    <input class="form-check-input" type="radio" name="rating"
                                                           value="3">
                                                    <input class="form-check-input" type="radio" name="rating"
                                                           value="4">
                                                    <input class="form-check-input" type="radio" name="rating"
                                                           value="5">
                                                    <span>Good</span>
                                                </div>
                                                @error('rating')<span
                                                    class="text-danger">{{$errors->first('rating')}}</span>@enderror
                                            </div>

                                            <button type="submit" class="btn btn-sm btn-success mt-3">Submit</button>

                                        </div>
                                    </div>
                                </form>
                            @endguest

                            @foreach(@$product->reviews as $review)
                                <div class="media">
                                    <img
                                        src="{{getImage(imagePath()['profile']['path'] . '/' . @$review->user->avatar, imagePath()['profile']['size'])}}"
                                        class="mr-3 rounded-circle profile-avatar-50" alt="User avatar">
                                    <div class="media-body">
                                        <h5 class="mt-0">{{@$review->user->full_name}}</h5>
                                        <small>
                                            @foreach(range(1,5) as $i)
                                                @if($i > $review->rating)
                                                    <i class="fa fa-star"></i>
                                                @else
                                                    <i class="fa fa-star text-success"></i>
                                                @endif
                                            @endforeach
                                            |
                                            {{showDiffForHuman($review->created_at)}}
                                        </small>
                                        <p class="my-2">{{$review->description}}</p>
                                    </div>
                                </div>
                                <hr>
                            @endforeach

                        </div>


                    </div>
                </div>


        </div>
    </section>
@endsection
@push('script')
    <!-- Lightbox -->
    <script src="{{asset('assets/plugins/lightbox/js/lightbox.min.js')}}"></script>

    <script>
        $(document).ready(function () {
            lightbox.option({
                'resizeDuration': 200,
                'wrapAround': true
            });
        });
    </script>
@endpush
