<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        abort_unless(Gate::allows('access-shop'), 403);

        $shops = Shop::latest()->get();

//        dd($shops);

        return view('admin.shop.index', compact('shops'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $this->validate($request, [
            'name' => 'required',
            'base_image' => 'mimes:jpeg,jpg,png|required|max:10000',
        ],
        ['base_image.required' => 'The image field is required.']);

        $data = $request->all();

        if($request->has('base_image')){
            $data['base_image'] = uploadImage($request->base_image, imagePath()['brand']['path'], imagePath()['brand']['size']);

        }

        $slug = Str::slug($data['name']);
        $data['slug'] = $this->checkSlugExists($slug);

        $request->has('status') ? $data['status'] = 1 : $data['status'] = 0;

        $shop = Shop::create($data);

        return redirect()->back()->with('success', trans('trans.created_successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function show(Shop $shop)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function edit(Shop $shop)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Shop $shop
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Shop $shop)
    {
        //        dd($request->all());
        $this->validate($request, [
            'name' => 'required',
        ]);
        $request['slug'] = Str::slug($request->name);
        $request->has('status') ? $request['status'] = 1 : $request['status'] = 0;

        $data = $request->except('_token');

        if($request->hasFile('logo_image')) {
            $old = $shop->logo_image ?: null;
            $data['logo_image'] = uploadImage($request->logo_image, imagePath()['brand']['path'], imagePath()['brand']['size'], $old);

        }else{
            $data['logo_image'] = '';
        }
//
        $shop->update($data);

        return redirect()->back()->with('success', trans('trans.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Shop $shop
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Shop $shop)
    {
        $shop->forceDelete();
        return redirect()->back()->with('success', trans('trans.deleted_successfully'));
    }


    /**
     * @param Shop $shop
     * @return \Illuminate\Http\RedirectResponse
     */
    public function statusUpdate(Shop $shop)
    {
        $shop->update([
            'status' => !$shop->status
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
        $checkExists = Shop::whereSlug($slug)->first();

        if ($checkExists !== null) {
            // generate again new txId
            $slug = $slug . '-' . $checkExists->id;

            // recursive the whole process again
            return $this->checkSlugExists($slug);

        } else {
            return $slug;
        }
    }
}
