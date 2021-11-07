<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        abort_unless(Gate::allows('access-category'), 403);
        $categories = Category::latest()->get();

        return view('admin.category.index', compact('categories'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
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
        $category = Category::create($data);
        Artisan::call('cache:clear');
        return redirect()->back()->with('success', trans('trans.created_successfully'));
    }

    /**
     * @param Request $request
     * @param Category $category
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Category $category)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
        $request['slug'] = Str::slug($request->name);
        $request->has('status') ? $request['status'] = 1 : $request['status'] = 0;

        $category->fill($request->only([
            'name',
            'category_id',
            'slug',
            'description',
            'status'
        ]));

        if ($category->isClean()) {
            return redirect()->back()->with('error', 'You need to specify a different value to update!!');
        }
        $category->save();
        Artisan::call('cache:clear');
        return  redirect()->back()->with('success', trans('trans.updated_successfully'));
    }

    /**
     * @param Category $category
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */

    public function destroy(Category $category)
    {
        $category->forceDelete();
        Artisan::call('cache:clear');
        return redirect()->back()->with('success', trans('trans.deleted_successfully'));
    }

    /**
     * Update resource status
     *
     * @param Category $category
     */
    public function statusUpdate(Category $category)
    {
        $category->update([
            'status' => !$category->status
        ]);

        Artisan::call('cache:clear');
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
        $checkExists = Category::whereSlug($slug)->first();

        if ($checkExists !== null) {
            // generate again new slug
            $slug = $slug . '-' . $checkExists->id;

            // recursive the whole process again
            return $this->checkSlugExists($slug);

        } else {
            return $slug;
        }
    }
}
