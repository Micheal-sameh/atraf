<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminLoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginPage()
    {
        return view('auth.login');
    }

    public function login(AdminLoginRequest $request)
    {
        $credentials = $request->only('membership_code', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            // if (! $user->hasRole(['admin'])) {
            //     Auth::logout();

            //     return redirect()->back()
            //         ->withInput($request->only('membership_code', 'remember'))
            //         ->withErrors(['membership_code' => __('messages.unauthorized')]);
            // }

            $request->session()->regenerate();

            return redirect()->intended(route('users.index'))->with('success', 'Welcome back, Admin!');
        }

        return redirect()->back()
            ->withInput($request->only('membership_code', 'remember'))
            ->withErrors(['membership_code' => __('auth.failed')]);
    }

    /**
     * Logout user and invalidate session
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(\Illuminate\Http\Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('loginPage')->with('success', __('messages.logged_out_successfully'));
    }
}
