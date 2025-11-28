<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ModuleEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $module): Response
    {
        $isEnabled = match ($module) {
            'projects'  => projectEnabled(),
            'gigs'      => gigEnabled(),
            'packages'  => packagesEnabled(),
            default => false
        };
        
        if (!$isEnabled) {
            return redirect()->back();
        }

        return $next($request);
    }
}
