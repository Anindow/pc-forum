<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_unless(Gate::allows('access-setting'), 403);
        $settings = $this->getSettings();
        $timezones = timezone_identifiers_list();
        return view('admin.setting.index', compact('settings', 'timezones'));
    }

    /**
     * No loops. Max 1 DB hit per request. Max 1 Cache hit per request
     *
     * @return array
     */
    public function getSettings()
    {
        $key = "settings.get";
        return Cache::remember($key, 24 * 60, function () {
            return Setting::pluck('value', 'key')->toArray();
        });
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        abort_unless(Gate::allows('access-setting'), 403);

        $this->validate($request, [
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|string|max:255',
        ]);

        $values = $request->except('_token');

        foreach ($values as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }

        Artisan::call('cache:clear');
        return redirect()->back()->with('success', trans('trans.updated_successfully'));;

    }
}
