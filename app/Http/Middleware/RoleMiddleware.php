<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Usage: ->middleware(['auth', 'role:admin,trainer'])
     * Accepts one or more comma-separated roles (mirrors is_admin() from the
     * old app, which allowed both 'admin' and 'trainer' into the admin side).
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        $allowed = explode(',', $roles);

        if (! $request->user() || ! in_array($request->user()->role, $allowed)) {
            abort(403, 'Unauthorized access for this role.');
        }

        return $next($request);
    }
}
