<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Auth;
use App\Models\UserSession;

class SingleSessionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $userSession = UserSession::where('user_id', Auth::id())->first();

            // If a session exists in DB and it doesn't match the current request session ID
            if ($userSession && $userSession->session_id !== session()->getId()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'session' => 'Multiple login detected. You have been logged out from this device.'
                ]);
            }

            // Update last activity
            if ($userSession) {
                $userSession->update(['last_activity' => now()]);
            }
        }

        return $next($request);
    }
}
