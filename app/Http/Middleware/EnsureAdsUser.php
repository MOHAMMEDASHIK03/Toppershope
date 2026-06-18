<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAdsUser
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('ads')->check()) {
            return redirect()->route('ads.login');
        }

        return $next($request);
    }
}
