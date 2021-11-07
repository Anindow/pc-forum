<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $divisions = Division::all();
        return view('admin.division.index', compact('divisions'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function allDivisions()
    {
        $countries = Division::with('districts', 'districts.upazilas')->get();
        return response()->json(['data' => $countries, 'code' => 200], 200);
    }

    /**
     * Change resource status
     *
     * @param Division $division
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function statusUpdate(Division $division)
    {
//        dd($division);
        $division->update([
            'status' => !$division->status
        ]);

        return redirect()->back()->with('success', trans('trans.updated_successfully'));

    }


}
