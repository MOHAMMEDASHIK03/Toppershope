<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureTrialStudent
{
    public function handle(Request $request, Closure $next)
    {
        $guard = Auth::guard('trial');

        if (!$guard->check()) {
            return redirect()->route('trial.login');
        }

        $trial = $guard->user();

        // Mark as expired if the time has passed
        if (!$trial->is_expired && $trial->expires_at->isPast()) {
            $trial->update(['is_expired' => true]);
            // Flag the linked contact for follow-up
            if ($trial->contact_id) {
                $trial->contact()->update(['needs_followup' => true]);
            }
        }

        if ($trial->is_expired) {
            $guard->logout();
            return redirect()->route('trial.login')->with('expired', true);
        }

        return $next($request);
    }
}
