<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EnsureFeatureEnabled
{
    public function handle(Request $request, Closure $next, string ...$features)
    {
        $config = config('pro_network_utilities_security_analytics.features', []);

        foreach ($features as $feature) {
            if (empty($config[$feature])) {
                throw new NotFoundHttpException();
            }
        }

        return $next($request);
    }
}
