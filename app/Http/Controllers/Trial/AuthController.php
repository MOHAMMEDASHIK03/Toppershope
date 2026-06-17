<?php

namespace App\Http\Controllers\Trial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('trial')->check()) {
            return redirect()->route('trial.dashboard');
        }
        return view('trial.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'trial_email' => ['required', 'email'],
            'password'    => ['required'],
        ]);

        $credentials = [
            'trial_email' => $request->trial_email,
            'password'    => $request->password,
        ];

        if (Auth::guard('trial')->attempt($credentials)) {
            $trial = Auth::guard('trial')->user();

            // Immediately check expiry on login
            if ($trial->is_expired || $trial->expires_at->isPast()) {
                $trial->update(['is_expired' => true]);
                if ($trial->contact_id) {
                    $trial->contact()->update(['needs_followup' => true]);
                }
                Auth::guard('trial')->logout();
                return back()->with('expired', true)->withInput(['trial_email' => $request->trial_email]);
            }

            $request->session()->regenerate();
            return redirect()->route('trial.dashboard');
        }

        return back()->withErrors(['trial_email' => 'Invalid trial credentials.'])->onlyInput('trial_email');
    }

    public function logout(Request $request)
    {
        Auth::guard('trial')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('trial.login');
    }
}
