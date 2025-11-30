<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (empty($roles)) {
            return $next($request);
        }

        $meta = getUserRole();
        $activeRole = data_get($meta, 'roleName');

        if (! $activeRole) {
            abort(403, 'This action requires a freelance role.');
        }

        if (! in_array($activeRole, $roles, true)) {
            abort(403, 'You are not authorised to access this freelance resource.');
        }

        return $next($request);
    }
}

