<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProductLinkController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Product $product)
    {
        abort_unless(Gate::allows('create-product-link'), 403);

        $this->validate($request, [
            'shop_id' => 'required',
            'price' => 'required',
            'url' => 'required',

        ],
        [
            'shop_id.required' => 'The shop field is required.'
        ]);

        $data = $request->all();

        $request->has('status') ? $data['status'] = 1 : $data['status'] = 0;

        $productLink = ProductLink::create([
            'shop_id' => $data['shop_id'],
            'product_id' => $product->id,
            'price' => $data['price'],
            'promo' => $data['promo'],
            'shipping' => $data['shipping'],
            'tax' => $data['tax'],
            'stock' => $data['stock'],
            'url' => $data['url'],
            'status' => $data['status'],
        ]);

        $product->load('productLinks');
        updateMinMaxProductPrice($product);

        return redirect()->back()->with('success', trans('trans.created_successfully'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param ProductLink $productLink
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Product $product, ProductLink $productLink)
    {
        abort_unless(Gate::allows('update-product-link'), 403);

        $this->validate($request, [
            'shop_id' => 'required',
            'price' => 'required',
            'url' => 'required',

        ],
            [
                'shop_id.required' => 'The shop field is required.'
            ]);

        $data = $request->all();

        $request->has('status') ? $data['status'] = 1 : $data['status'] = 0;

        $productLink = $productLink->update([
            'shop_id' => $data['shop_id'],
            'product_id' => $product->id,
            'price' => $data['price'],
            'promo' => $data['promo'],
            'shipping' => $data['shipping'],
            'tax' => $data['tax'],
            'stock' => $data['stock'],
            'url' => $data['url'],
            'status' => $data['status'],
        ]);

        $product->load('productLinks');
        updateMinMaxProductPrice($product);

        return redirect()->back()->with('success', trans('trans.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @param ProductLink $productLink
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product, ProductLink $productLink)
    {
        abort_unless(Gate::allows('delete-product-link'), 403);

        $productLink->forceDelete();
        return redirect()->back()->with('success', trans('trans.deleted_successfully'));
    }

    /**
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function statusUpdate(Product $product, ProductLink $productLink)
    {
        abort_unless(Gate::allows('status-change-product-link'), 403);
//        dd('paic');
        $productLink->update([
            'status' => !$productLink->status
        ]);

        return redirect()->back()->with('success', trans('trans.updated_successfully'));

    }
}
