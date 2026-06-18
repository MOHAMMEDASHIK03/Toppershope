<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureHrUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('hr')->check()) {
            return redirect()->route('hr.login')->with('error', 'Please log in to access the HR Panel.');
        }

        $hrUser = Auth::guard('hr')->user();
        
        if (!$hrUser->is_active) {
            Auth::guard('hr')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('hr.login')->with('error', 'Your account has been deactivated. Please contact support.');
        }

        return $next($request);
    }
}
