<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EnforceAdminPrefix
{
    public function handle(Request $request, Closure $next)
    {
        $prefix = trim(config('app.admin_prefix', 'admin'), '/');

        if ($prefix !== 'admin' && Str::startsWith($request->path(), 'admin')) {
            if ($request->headers->get('X-Gigvora-Admin-Forward') === '1') {
                return $next($request);
            }

            $redirectPath = admin_path(Str::after($request->path(), 'admin'));

            return redirect($redirectPath ?: '/' . $prefix);
        }

        return $next($request);
    }
}
