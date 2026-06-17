<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAdmissionUser
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('admission')->check()) {
            return redirect()->route('admission.login');
        }

        if (!Auth::guard('admission')->user()->is_active) {
            Auth::guard('admission')->logout();
            return redirect()->route('admission.login')->with('error', 'Your account has been deactivated.');
        }

        return $next($request);
    }
}
