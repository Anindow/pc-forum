<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $dashboardData = [];
        $dashboardData['total_user'] = User::active()->where('deleted_at', NULL)->count();
        $dashboardData['total_product'] = Product::active()->where('deleted_at', NULL)->count();
        $dashboardData['total_brand'] = Brand::active()->where('deleted_at', NULL)->count();
        $dashboardData['total_shop'] = Shop::active()->where('deleted_at', NULL)->count();

        $dashboardData = (object) $dashboardData;

        return view('admin.dashboard.index', compact('dashboardData'));
    }
}
