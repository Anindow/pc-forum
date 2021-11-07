<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Division;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $districts = District::all();
        return view('admin.district.index', compact('districts'));
    }

    /**
     * Get all districts from target division.
     *
     * @param Division $division
     * @return \Illuminate\Http\JsonResponse
     */
    public function divisionDistricts(Division $division)
    {
        $division->load('districts', 'districts.upazilas');
        return response()->json(['data' => $division->districts, 'code' => 200], 200);
    }



    /**
     * Change resource status
     *
     * @param District $district
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function statusUpdate(District $district)
    {
        $district->update([
            'status' => !$district->status
        ]);

        return redirect()->back()->with('success', trans('trans.updated_successfully'));

    }
}
