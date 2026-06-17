<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureAdminUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        // Optional: you can ensure they are strictly super admin if you plan to have sub-admins later
        if (!Auth::guard('admin')->user()->is_super_admin) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')->with('error', 'Unauthorized access.');
        }

        return $next($request);
    }
}
