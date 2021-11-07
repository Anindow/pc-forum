<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ReviewController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin')->except('store');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        abort_unless(Gate::allows('access-review'), 403);
        $reviews = Review::with('product:id,name,slug', 'user:id,first_name,last_name')->orderBy('status', 'asc')->get();

//        dd($reviews);
        return view('admin.review.index', compact('reviews'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, Product $product)
    {
        $this->validate($request, [
            'description' => 'required|string|max:255',
            'rating' => 'required',
        ], [
            'description.required' => 'The review field is required'
        ]);

        $data = $request->all();

        $review = Review::create([
            'description' => scriptStripper($data['description']),
            'user_id' => auth()->user()->id,
            'product_id' => $product->id,
            'rating' => $data['rating'],
        ]);

        return redirect()->back()->with('success', trans('trans.created_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Review $review
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Review $review)
    {
//        $review->forceDelete();
        $review->delete();
        return redirect()->back()->with('success', trans('trans.deleted_successfully'));
    }

    /**
     * Update status field of the resource.
     *
     * @param Review $review
     * @return \Illuminate\Http\RedirectResponse
     */
    public function statusUpdate(Review $review)
    {
        $review->update([
            'status' => !$review->status
        ]);

        return redirect()->back()->with('success', trans('trans.updated_successfully'));

    }
}
