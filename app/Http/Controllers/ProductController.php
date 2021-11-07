<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductLink;
use App\Models\Shop;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        abort_unless(Gate::allows('access-product'), 403);

        $products = Product::with('brand:id,name', 'categories:id,name', 'tags:id,name', 'productImages:id,product_id,name')->latest()->get();
        $brands = Brand::where('status', 1)->latest()->get();
        $categories = Category::where('status', 1)->latest()->get();
        $tags = Tag::where('status', 1)->latest()->get();

//        dd($products);

        return view('admin.product.index', compact('products', 'brands', 'categories', 'tags'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Product $product
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Product $product)
    {
        abort_unless(Gate::allows('access-product-link'), 403);

        $product->load('productLinks', 'productLinks.shop:id,name', 'productLinks.product:id,name,slug');

        $productLinks = $product->productLinks;

        $existingShops = $productLinks->pluck('shop_id');

        $shops = Shop::active()
            ->latest()
            ->get();

        return view('admin.product.product-link', compact('product', 'productLinks', 'shops', 'existingShops'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        abort_unless(Gate::allows('create-product'), 403);

        $this->validate($request, [
            'name' => 'required',
            'brand_id' => 'required',
            'base_image' => 'mimes:jpeg,jpg,png|required|max:10000',
        ],
            [
                'base_image.required' => 'The image field is required.'
            ]
        );

//        dd($request->all());

        $data = $request->except('_token', 'category_id', 'tag_id', 'images');

        if ($request->has('base_image')) {
            $data['base_image'] = uploadImage($request->base_image, imagePath()['product']['path'], imagePath()['product']['size']);

        }

        $slug = Str::slug($data['name']);
        $data['slug'] = $this->checkSlugExists($slug);

        $request->has('status') ? $data['status'] = 1 : $data['status'] = 0;

        $product = Product::create($data);
//        dd('paici');

        if ($product) {
            $product->categories()->sync($request->category_id);
            $product->tags()->sync($request->tag_id);

            if ($request->has('images')) {
                $files = $request->file('images');
                foreach ($files as $image) {
                    $name = uploadImage($image, imagePath()['product']['path'], imagePath()['product']['size']);

                    ProductImage::create([
                        'product_id' => $product->id,
                        'name' => $name
                    ]);

                }
            }

        }

        return redirect()->back()->with('success', trans('trans.created_successfully'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Product $product)
    {
        abort_unless(Gate::allows('update-product'), 403);
//                dd($request->all());
        $this->validate($request, [
            'name' => 'required',
            'brand_id' => 'required',
        ],
            [
                'brand_id.required' => 'The brand field is required.'
            ]
        );
        $request['slug'] = Str::slug($request->name);
        $request->has('status') ? $request['status'] = 1 : $request['status'] = 0;

        $data = $request->except('_token', 'category_id', 'tag_id', 'images');

        $product->categories()->sync($request->category_id);
        $product->tags()->sync($request->tag_id);

        if ($request->hasFile('base_image')) {
            $old = $product->base_image ?: null;
            $data['base_image'] = uploadImage($request->base_image, imagePath()['product']['path'], imagePath()['product']['size'], $old);

        }

        $product->update($data);

        if ($request->has('images')) {
            $files = $request->file('images');

            foreach ($files as $image) {

                $name = uploadImage($image, imagePath()['product']['path'], imagePath()['product']['size']);

                ProductImage::create([
                    'product_id' => $product->id,
                    'name' => $name
                ]);

            }
        }

        return redirect()->back()->with('success', trans('trans.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        abort_unless(Gate::allows('delete-product'), 403);
        $product->forceDelete();
        return redirect()->back()->with('success', trans('trans.deleted_successfully'));
    }


    /**
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function statusUpdate(Product $product)
    {
        abort_unless(Gate::allows('status-change-product'), 403);
        $product->update([
            'status' => !$product->status
        ]);

        return redirect()->back()->with('success', trans('trans.updated_successfully'));

    }


    /**
     * Check duplicate slug. If exists generate new one and repeat check again.
     *
     * @param $slug
     * @return mixed
     */
    public function checkSlugExists($slug)
    {
        $checkExists = Product::whereSlug($slug)->first();

        if ($checkExists !== null) {
            // generate again new txId
            $slug = $slug . '-' . $checkExists->id;

            // recursive the whole process again
            return $this->checkSlugExists($slug);

        } else {
            return $slug;
        }
    }

    public function getFilterProducts(Request $request)
    {

        $min_price = $request->price_range['min'];
        $max_price = $request->price_range['max'];

        $products = Product::with('categories', 'brand', 'tags')
            ->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->category);
            })
            ->whereHas('brand', function ($q) use ($request) {
                if ($request->brand) {
                    $q->whereIn('slug', $request->brand);
                }
            })
            ->whereHas('tags', function ($q) use ($request) {
                if ($request->tag) {
                    $q->whereIn('slug', $request->tag);
                }
            })
            ->whereBetween('min_price', [$min_price, $max_price])
            ->whereBetween('max_price', [$min_price, $max_price])
            ->get();


        return response()->json(['products' => $products, 'draw' => $request->draw]);

    }
}
