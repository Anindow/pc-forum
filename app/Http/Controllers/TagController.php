<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        abort_unless(Gate::allows('access-tag'), 403);

        $tags = Tag::latest()->get();
//        $categories = $this->getCategories(); //for cache example

//        dd($tags);

        return view('admin.tag.index', compact('tags'));
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
        $tag = Tag::create($data);
        return redirect()->back()->with('success', trans('trans.created_successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Tag $tag
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Tag $tag)
    {
        //        dd($request->all());
        $this->validate($request, [
            'name' => 'required',
        ]);
        $request['slug'] = Str::slug($request->name);
        $request->has('status') ? $request['status'] = 1 : $request['status'] = 0;

//        dd($request->all());
        $tag->fill($request->only([
            'name',
            'slug',
            'status'
        ]));

        if ($tag->isClean()) {
            return redirect()->back()->with('error', 'You need to specify a different value to update!!');
        }
        $tag->save();
        return redirect()->back()->with('success', trans('trans.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Tag $tag
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Tag $tag)
    {
//        $tag->forceDelete();
        $tag->delete();
        return redirect()->back()->with('success', trans('trans.deleted_successfully'));
    }

    /**
     * @param Tag $tag
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function statusUpdate(Tag $tag)
    {
        $tag->update([
            'status' => !$tag->status
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
        $checkExists = Tag::whereSlug($slug)->first();

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
