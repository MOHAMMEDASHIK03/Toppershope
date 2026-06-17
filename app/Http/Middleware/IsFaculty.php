<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsFaculty
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('faculty.login')->with('error', 'Please log in to access the faculty panel.');
        }

        // 2. Check if the user has the 'faculty' or 'admin' role
        if (!Auth::user()->isFaculty()) {
            // Log them out and redirect if they are just a student
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('faculty.login')->with('error', 'Unauthorized access. Only faculty members can access this area.');
        }

        return $next($request);
    }
}
