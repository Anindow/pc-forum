<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        abort_unless(Gate::allows('access-user'), 403);

        $roles = Role::with('createdBy:id,first_name,last_name', 'permissions:id,name,for')
            ->where('created_by', Auth::user()->id)
            ->orderBy('name')
            ->latest()
            ->pluck('name', 'id');

//        $users = User::with('roles:id,name', 'division', 'district', 'upazila')
        $users = User::with('roles:id,name')
            ->latest()
            ->get();

        return view('admin.user.index', compact('users', 'roles'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     */
    public function store(Request $request)
    {
        abort_unless(Gate::allows('create-user'), 403);

        $this->validate($request, [
            'first_name' => 'required|string|max:255',
//            'phone' => 'required|string|max:20|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'role_id' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ],
            [
                'role_id.required' => 'You have to select at least one role!!'
            ]
        );

        $request->has('status') ? $request['status'] = 1 : $request['status'] = 0;
        $data = $request->except('role_id', '_token');

        if ($request->hasFile('avatar')) {
            $data['avatar'] = uploadImage($request->avatar, imagePath()['profile']['path'], imagePath()['profile']['size']);
        }else{
            $data['avatar'] = '';
        }

        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'under_by' => Auth::user()->id,
            'is_admin' => 1,
            'division_id' => $data['division_id'],
            'district_id' => $data['district_id'],
            'upazila_id' => $data['upazila_id'],
            'address' => $data['address'],
            'avatar' => $data['avatar'],
            'password' => bcrypt($data['password']),
            'status' => $data['status'],
        ]);

        $user->roles()->sync($request->role_id);

        return redirect()->back()->with('success', trans('trans.created_successfully'));
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     */
    public function update(Request $request, User $user)
    {
        abort_unless(Gate::allows('update-user'), 403);

        $this->validate($request, [
            'first_name' => 'required|string|max:255',
//            'phone' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role_id' => 'required',
        ],
            [
                'role_id.required' => 'You have to select at least one role!!'
            ]
        );

        $request->has('status') ? $request['status'] = 1 : $request['status'] = 0;
        $data = $request->except('role_id', '_token');

        if($request->hasFile('avatar')) {
            $old = $user->avatar ?: null;
            $data['avatar'] = uploadImage($request->avatar, imagePath()['profile']['path'], imagePath()['profile']['size'], $old);
        }else{
            $data['avatar'] = '';
        }

        $user->update($data);

        $user->roles()->sync($request->role_id);

        return redirect()->back()->with('info', trans('trans.updated_successfully'));
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        abort_unless(Gate::allows('delete-user'), 403);
        $user->forceDelete();
        return redirect()->back()->with('success', trans('trans.deleted_successfully'));
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile(User $user)
    {
        abort_unless($user->id == auth()->user()->id, 403);
        return view('admin.user.profile', compact('user'));
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function profileUpdate(Request $request, User $user)
    {
        abort_unless($user->id == auth()->user()->id, 403);
        $this->validate($request, [
                'first_name' => 'required|max:255',
                'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
                'avatar' => 'image|mimes:jpeg,png|max:1024',
            ]
        );

        $data = $request->all();

        if ($request->hasFile('avatar')) {
            $old = $user->avatar ?: null;

            if ($old == 'default-profile.png') {
                $old = null;
            }

            $data['avatar'] = uploadImage($request->avatar, imagePath()['profile']['path'], imagePath()['profile']['size'], $old);
        }

        $user->update($data);

        return redirect()->back()->with('success', trans('trans.updated_successfully'));

    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function passwordUpdate(Request $request, User $user)
    {
        abort_unless($user->id == auth()->user()->id, 403);
        $this->validate($request, [
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed'
        ]);

        if (Auth::check() && $user->id == auth()->user()->id) {
            if (Hash::check($request->current_password, $user->password)) {
                $user = User::findOrFail($user->id);
                $user->password = bcrypt($request->password);
                $user->save();
                return redirect()->back()->with('success', trans('trans.password_changed_successfully'));
            } else {
                return redirect()->back()->with('error', trans('trans.current_password_doesnt_match'));
            }

        } else {
            return redirect()->to('/');
        }
    }

    /**
     * Update user status.
     *
     * @param User $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function statusUpdate(User $user)
    {
        $user->update([
            'status' => !$user->status
        ]);

        return redirect()->back()->with('success', trans('trans.updated_successfully'));

    }
}
