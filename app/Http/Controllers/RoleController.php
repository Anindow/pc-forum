<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class RoleController extends Controller
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
     * Show all roles with permissions
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        abort_unless(Gate::allows('access-role'), 403);

        $permissions = Permission::latest()->get();

        $userType = Auth::user()->type;
        $roles = Role::with('createdBy:id,first_name,last_name', 'permissions:id,name,for')
            ->where(function ($q) use ($userType) {
                if ($userType != 1) {
                    $q->where('created_by', Auth::user()->id);
                }
            })
            ->latest()
            ->get();

        return view('admin.role.index', compact('roles', 'permissions'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        abort_unless(Gate::allows('create-role'), 403);
        $this->validate($request, [
            'name' => 'required|string|max:255'
        ]);

        $data = $request->except('permission_id', '_token');
        $data['slug'] = Str::slug($data['name']);
        $data['created_by'] = Auth::user()->id;

//        dd($data);
        $role = Role::create($data);

        $role->permissions()->sync($request->permission_id);

        return redirect()->back()->with('success', trans('trans.created_successfully'));
    }

    /**
     * @param Request $request
     * @param Role $role
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Role $role)
    {
        abort_unless(Gate::allows('update-role'), 403);
        abort_unless($role->created_by == Auth::user()->id, 403);

        $this->validate($request, [
            'name' => 'required|string'
        ]);

        $request['slug'] = Str::slug($request->name);
        $role->fill($request->only([
            'name',
            'slug',
            'description'
        ]));

        if ($role->isClean() && !$request->has('permission_id')) {
            return redirect()->back()->with('error', 'You need to specify a different value to update!!');
        }
        $role->save();
        $role->permissions()->sync($request->permission_id);

        return redirect()->back()->with('success', trans('trans.updated_successfully'));
    }

    /**
     * @param Role $role
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(Role $role)
    {
        abort_unless(Gate::allows('delete-role'), 403);

        if ($role->users->count() == 0) {
//            $role->forceDelete();
            $role->delete();
            return redirect()->back()->with('success', trans('trans.deleted_successfully'));
        } else {
            return redirect()->back()->with('error', trans('trans.this_field_already_used_in_somewhere', ['field' => 'role']));
        }
    }
}
