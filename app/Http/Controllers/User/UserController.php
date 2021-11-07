<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

//    /**
//     * @param User $user
//     * @return \Illuminate\Http\RedirectResponse
//     * @throws \Exception
//     */
//    public function destroy(User $user)
//    {
//        abort_unless(Gate::allows('delete-user'), 403);
//        $user->forceDelete();
//        return redirect()->back()->with('success', trans('trans.deleted_successfully'));
//    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile(User $user)
    {
        abort_unless($user->id == auth()->user()->id, 403);

//        dd('ppp');
        return view('frontend.user.profile', compact('user'));
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
//        dd($request->all());
        $this->validate($request, [
                'first_name' => 'required|max:255',
                'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
                'avatar' => 'image|mimes:jpeg,png|max:1024',
            ]
        );

        $data = $request->except('_method', '_token');

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
}
