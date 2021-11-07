@extends('frontend.layouts.app')

@section('title', trans('trans.home') . ' |')

@section('content')
    <!--Slider Start-->
    <div id="carouselExampleIndicators" class="carousel carousel-slider slide" data-ride="carousel">
        <ol class="carousel-indicators">
            @foreach($sliders as $key => $slider)
                <li data-target="#carouselExampleIndicators"
                    class="{{$key==0?'active' : ''}}" data-slide-to="{{$key}}"></li>
            @endforeach
        </ol>
        <div class="carousel-inner">
            @foreach($sliders as $key => $slider)
            <div class="carousel-item {{$key==0?'active' : ''}}">
                <img src="{{getImage(imagePath()['slider']['path'] . '/' . $slider->image, imagePath()['slider']['size'])}}" class="d-block w-100" alt="...">
            </div>
            @endforeach
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
    <!--Slider End-->

    <!-- product Start -->
    <section class="py-5" id="product">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="text-center mb-5">
                        <h2 class="text-uppercase mb-4">New Product</h2>
                        <div class="single-line"></div>
                    </div>
                </div>
            </div>
            <div class="row">

                @foreach($products as $product)
                <div class="col-md-3 box">
                    <a href="{{route('product.detail', $product->slug)}}" class="nav-link text-dark">
                        <div class="product-box text-center p-4">
                            <div class=" mt-3">
                                <img src="{{getImage(imagePath()['product']['path'] . '/' . $product->base_image, imagePath()['product']['size'])}}" alt="" class="img-fluid mx-auto d-block p-4">
                            </div>
                            <h4 class="mb-3">{{$product->name}}</h4>
                            <p class="text-muted f-15">{{substr(strip_tags($product->sort_description),0,50)."..."}}
                            </p>
                        </div>
                    </a>
                </div>
                @endforeach


            </div>
        </div>
    </section>


    <section class="py-5" id="new-product">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="text-center mb-5">
                        <h2 class="text-uppercase mb-4">What's New</h2>
                        <div class="single-line"></div>
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                @foreach($banners as $banner)
                <div class="col-sm-4">
                    <div class="p-2">
                        <a href="{{route('product.detail', $product->slug)}}">
                            <img src="{{getImage(imagePath()['banner']['path'] . '/' . $banner->image, imagePath()['banner']['size'])}}"  alt="" class="img-fluid  d-block ">
                        </a>
                    </div>
                </div>
                @endforeach
            </div>


        </div>
    </section>
@endsection
