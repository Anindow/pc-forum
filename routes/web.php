<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ==============================================================
// Auth Section
// ==============================================================
//Auth::routes(['register' => false]);
Auth::routes();

// Locale
Route::get('/locale/{locale}', function ($locale) {
    Session::put('locale', $locale);
    return redirect()->back()->with('success', trans('trans.language_changed_successfully'));
})->name('locale.set');


//Route::get('/', function () {
//    if (Auth::check()) {
//        return redirect('/admin/dashboard');
//    } else {
//        return view('admin.auth.login');
//    }
//});

// ==============================================================
// Frontend Section
// ==============================================================
Route::get('/', 'SiteController@index');

// ==============================================================
// Contact Section
// ==============================================================
Route::get('/contact', 'SiteController@showContactPage')->name('frontend.contact');

// ==============================================================
// About Section
// ==============================================================
Route::get('/about', 'SiteController@showAboutPage')->name('frontend.about');

// ==============================================================
// Category Product Section
// ==============================================================
Route::get('/search', 'SiteController@search')->name('product.search');
Route::get('/{category}', 'SiteController@getCategoryProduct')->name('category.product');

Route::get('/products/{product}', 'SiteController@getProductDetails')->name('product.detail');
Route::post('/reviews/products/{product}', 'ReviewController@store')->name('reviews.store');



// ==============================================================
// Price Alert
// ==============================================================
Route::post('/alerts/products/{product}', 'PriceAlertController@store')->name('alerts.store');

// ==============================================================
// Helper Section
// ==============================================================
//Helper routes with eager loading
Route::get('/divisions/all', 'DivisionController@allDivisions')->name('divisions.all');
Route::get('/divisions/{division}/districts', 'DistrictController@divisionDistricts')->name('divisions.districts.all');
Route::get('/districts/{district}/upazilas', 'UpazilaController@districtUpazilas')->name('districts.upazilas.all');



//Route::group(['middleware' => ['auth']], function () {
//Route::name('users.')->group(function () {
    Route::group(['middleware' => ['auth']], function () {
        Route::get('/profile/{user}', 'User\UserController@profile')->name('profile');
        Route::put('/profile-update/{user}', 'User\UserController@profileUpdate')->name('profile-update');
        Route::post('/password-update/{user}', 'User\UserController@passwordUpdate')->name('password-update');
    });
//});




//Route::get('/admin', 'Admin\AdminController@index')->name('admin');
Route::get('/admin/login', 'Auth\AdminLoginController@adminLogin')->name('admin.login');


Route::prefix('admin')->group(function () {

    Route::group(['middleware' => ['auth', 'admin']], function () {

// ==============================================================
// Admin Section
// ==============================================================

        Route::get('/dashboard', 'DashboardController@index')->name('dashboard');


        Route::get('/logout', 'Auth\LoginController@logout')->name('admin.logout');

// ==============================================================
// Roles Section
// ==============================================================
        Route::resource('/roles', 'RoleController');

// ==============================================================
// Users Section
// ==============================================================
        Route::get('/users/{user}/profile', 'UserController@profile')->name('admin.users.profile');
        Route::put('/users/{user}/profile-update', 'UserController@profileUpdate')->name('users.profile-update');
        Route::post('/users/{user}/password-update', 'UserController@passwordUpdate')->name('users.password-update');
        Route::get('/users/{user}/status-update', 'UserController@statusUpdate')->name('users.status-update');
        Route::resource('/users', 'UserController');

// ==============================================================
// Categories Section
// ==============================================================
        Route::get('/categories/status/update/{category}', 'CategoryController@statusUpdate')->name('categories.status.update');
        Route::resource('/categories', 'CategoryController');

// ==============================================================
// Tags Section
// ==============================================================
        Route::get('/tags/status/update/{tag}', 'TagController@statusUpdate')->name('tags.status.update');
        Route::resource('/tags', 'TagController', ['except' => ['create', 'edit', 'show']]);

// ==============================================================
// Brands Section
// ==============================================================
        Route::get('/brands/status/update/{brand}', 'BrandController@statusUpdate')->name('brands.status.update');
        Route::resource('/brands', 'BrandController', ['except' => ['create', 'edit', 'show']]);


// ==============================================================
// Products Section
// ==============================================================
        Route::get('/products/status/update/{product}', 'ProductController@statusUpdate')->name('products.status.update');
        Route::resource('/products', 'ProductController', ['except' => ['create', 'show']]);

// ==============================================================
// Product Links Section
// ==============================================================
        Route::get('/products/{product}/productLinks/{productLink}/status/update', 'ProductLinkController@statusUpdate')->name('productLinks.status.update');
        Route::resource('/products/{product}/productLinks', 'ProductLinkController');

// ==============================================================
// Product Reviews Section
// ==============================================================
        Route::get('/reviews/{review}/status/update', 'ReviewController@statusUpdate')->name('reviews.status.update');
        Route::get('/reviews', 'ReviewController@index')->name('reviews.index');
        Route::delete('/reviews{review}', 'ReviewController@destroy')->name('reviews.destroy');

// ==============================================================
// Shops Section
// ==============================================================
        Route::get('/shops/status/update/{shop}', 'ShopController@statusUpdate')->name('shops.status.update');
        Route::resource('/shops', 'ShopController', ['except' => ['create', 'edit', 'show']]);


// ==============================================================
// Slider Section
// ==============================================================
        Route::get('/sliders/{slider}/status/update', 'SliderController@statusUpdate')->name('sliders.status.update');
        Route::resource('/sliders', 'SliderController', ['except' => ['create', 'edit', 'show']]);

// ==============================================================
// Banner Section
// ==============================================================
        Route::get('/banners/{banner}/status/update', 'BannerController@statusUpdate')->name('banners.status.update');
        Route::resource('/banners', 'BannerController', ['except' => ['create', 'edit', 'show']]);

// ==============================================================
// Settings Section
// ==============================================================
        Route::resource('/settings', 'SettingController');

// ==============================================================
// Division Section
// ==============================================================
        Route::get('/divisions/{division}/status/update', 'DivisionController@statusUpdate')->name('divisions.status-update');
        Route::get('/divisions', 'DivisionController@index')->name('divisions.index');

// ==============================================================
// District Section
// ==============================================================
        Route::get('/districts/{district}/status/update', 'DistrictController@statusUpdate')->name('districts.status-update');
        Route::get('/districts', 'DistrictController@index')->name('districts.index');

// ==============================================================
// Upazila Section
// ==============================================================
        Route::get('/upazilas/{upazila}/status/update', 'UpazilaController@statusUpdate')->name('upazilas.status-update');
        Route::get('/upazilas', 'UpazilaController@index')->name('upazilas.index');


    });
});





