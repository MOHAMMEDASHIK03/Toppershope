<?php

namespace App\Http\Controllers\Ads;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('ads')->check()) {
            return redirect()->route('ads.dashboard');
        }
        return view('ads.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('ads')->attempt(array_merge($credentials, ['is_active' => true]), $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('ads.dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials or your account has been deactivated.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('ads')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('ads.login');
    }
}
