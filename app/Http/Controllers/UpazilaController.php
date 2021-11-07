<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Upazila;
use Illuminate\Http\Request;

class UpazilaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $upazilas = Upazila::all();
        return view('admin.upazila.index', compact('upazilas'));
    }

    /**
     * Get all upazilas from target district.
     *
     * @param District $district
     * @return \Illuminate\Http\JsonResponse
     */
    public function districtUpazilas(District $district)
    {
        return response()->json(['data' => $district->upazilas, 'code' => 200], 200);
    }

    /**
     * Change resource status
     *
     * @param Upazila $upazila
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function statusUpdate(Upazila $upazila)
    {
        $upazila->update([
            'status' => !$upazila->status
        ]);

        return redirect()->back()->with('success', trans('trans.updated_successfully'));

    }
}
