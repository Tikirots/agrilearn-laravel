<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountActive
{
    /**
     * Logs out anyone whose account is no longer 'active' — mirrors the old
     * login-time status check, but also protects already-open sessions if an
     * admin rejects/deactivates the account mid-session.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->status !== 'active') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('error', $user->status === 'pending'
                    ? 'Your registration is still pending admin approval.'
                    : 'Your account is inactive. Please contact the training center.');
        }

        return $next($request);
    }
}
