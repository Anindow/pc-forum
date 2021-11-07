<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    // Available locales in app
    private $locales = ['en', 'de'];

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Session::has('locale')) {
            $locale = Session::get('locale');
            if (array_search($locale, $this->locales) === false) {
                App::setLocale('en');
            } else {
                App::setLocale($locale);
            }

        }
        return $next($request);
    }
}
