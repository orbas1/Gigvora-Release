<?php

namespace App\Support\Authorization;

use Closure;
use Illuminate\Http\Request;

class PermissionMiddleware
{
    public function __construct(protected PermissionMatrix $matrix)
    {
    }

    public function handle(Request $request, Closure $next, string $permission)
    {
        if (! $this->matrix->allowed($request->user(), $permission)) {
            abort(403, 'You are not authorised to perform this action.');
        }

        return $next($request);
    }
}
