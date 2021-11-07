<?php

namespace App\Http\Controllers;

use App\Models\PriceAlert;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class PriceAlertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, Product $product)
    {
        $this->validate($request, [
            'alert_price' => 'required|regex:/^\d+(\.\d{1,2})?$/'
        ],
            ['alert_price.required' => 'The Price field is required.']);


        $user = User::with('priceAlerts')->findOrFail(auth()->user()->id);

        if($user->priceAlerts->contains($product->id)){
            return redirect()->back()->with('error', trans('trans.price_alert_already_exists'));
        }else {
            PriceAlert::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'alert_price' => $request->alert_price,
            ]);
            return redirect()->back()->with('success', trans('trans.price_alert_set_successfully'));
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PriceAlert  $priceAlert
     * @return \Illuminate\Http\Response
     */
    public function show(PriceAlert $priceAlert)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PriceAlert  $priceAlert
     * @return \Illuminate\Http\Response
     */
    public function edit(PriceAlert $priceAlert)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PriceAlert  $priceAlert
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PriceAlert $priceAlert)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PriceAlert  $priceAlert
     * @return \Illuminate\Http\Response
     */
    public function destroy(PriceAlert $priceAlert)
    {
        //
    }
}
