<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductLink;
use App\Models\Slider;
use App\Models\Tag;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    /**
     * Show frontend home page.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
//        dd('djsjh');


        $sliders = Slider::where('status', 1)->latest()->take(4)->get();
        $products = Product::where('status', 1)->latest()->take(4)->get();
        $banners = Banner::where('status', 1)->latest()->take(6)->get();

//        dd($products);

        return view('frontend.home.index', compact('sliders', 'products', 'banners'));
//        return view('frontend.layouts.app');
    }

    /**
     * Show frontend contact page.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showContactPage()
    {
        return view('frontend.contact.index');
    }

    /**
     * Show frontend contact page.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAboutPage()
    {
        return view('frontend.about.index');
    }

    /**
     * Show frontend contact page.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCategoryProduct(Request $request, Category $category)
    {
        if ($request->ajax()) {

            $category->load([
                'products' => function ($q) use ($request) {
                    $q->whereHas('brand', function ($q2) use ($request) {
                        $q2->where('brand.slug', 'hp');
                    });
                },
                'products.brand',
//                'products.slug'
            ]);

            return $category;
        }

        $category->load('products', 'products.brand');
        $products = $category->products;

        $brands = Brand::where('status', 1)->latest()->get();
        $tags = Tag::where('status', 1)->latest()->get();
        return view('frontend.product.product-list', compact('category', 'products', 'brands', 'tags'));
    }

    /**
     * Show frontend product details page.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getProductDetails(Product $product)
    {
        $product->load([
            'reviews' => function ($q) {
                $q->where('status', 1);
            },
            'reviews.user:id,first_name,last_name,avatar',
            'productImages',
            'brand',
            'productLinks',
            'productLinks.shop'
        ]);
//       dd($product);

        return view('frontend.product.product-detail', compact('product'));
    }

    /**
     * Search frontend navbar page.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request)
    {
        $products = [];
        $q = '';
        if ($request->has('q')) {
            $q = $request->q;
            $products = Product::where('name', 'LIKE', "%$q%")
                ->where('status', 1)
                ->get();
//            dd($products);
        }


        $brands = Brand::where('status', 1)->latest()->get();
        $tags = Tag::where('status', 1)->latest()->get();

        return view('frontend.product.product-list', compact('products','brands', 'tags', 'q'));
    }

}
