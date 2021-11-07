<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        abort_unless(Gate::allows('access-brand'), 403);

        $brands = Brand::latest()->get();

//        dd($brands);

        return view('admin.brand.index', compact('brands'));
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
        ]);
        $data = $request->all();
        $slug = Str::slug($data['name']);
        $data['slug'] = $this->checkSlugExists($slug);
        $request->has('status') ? $data['status'] = 1 : $data['status'] = 0;
        $brand = Brand::create($data);
        return redirect()->back()->with('success', trans('trans.created_successfully'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Brand $brand
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Brand $brand)
    {
        //        dd($request->all());
        $this->validate($request, [
            'name' => 'required',
        ]);
        $request['slug'] = Str::slug($request->name);
        $request->has('status') ? $request['status'] = 1 : $request['status'] = 0;

//        dd($request->all());
        $brand->fill($request->only([
            'name',
            'slug',
            'status'
        ]));

        if ($brand->isClean()) {
            return redirect()->back()->with('error', 'You need to specify a different value to update!!');
        }
        $brand->save();
        return redirect()->back()->with('success', trans('trans.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Brand $brand
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Brand $brand)
    {
        $brand->forceDelete();
        return redirect()->back()->with('success', trans('trans.deleted_successfully'));
    }

    /**
     * @param Brand $brand
     * @return \Illuminate\Http\RedirectResponse
     */
    public function statusUpdate(Brand $brand)
    {
        $brand->update([
            'status' => !$brand->status
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
        $checkExists = Brand::whereSlug($slug)->first();

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
