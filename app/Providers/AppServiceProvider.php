<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

//        dd($this->getCategories());

        view()->composer('frontend.includes.navbar', function ($view) {
            $view->with([
                'categories' => $this->getCategories(),
            ]);
        });


        //set the timezone globally
        if (Schema::hasTable('settings')){
            Config::set('app.timezone', settings('timezone'));
        }
    }

    protected function getCategories($count = null)
    {
        if (Schema::hasTable('categories')) {

            $key = "categories.get{$count}";
            return Cache::rememberForever($key,  function () use ($count) {
                return Category::with('categories')->take($count)->get(['id', 'name', 'slug', 'category_id']);
            });

        } else {
            return [];
        }
    }
}
