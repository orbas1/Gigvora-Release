<?php

namespace App\Http\Middleware;

use Amentotech\LaraPayEase\Facades\PaymentDriver;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerfiyPaymentGateway
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $gateway = $request->segment(1);
        $gateways = PaymentDriver::supportedGateways();
        if(!array_key_exists($gateway, $gateways)){
            return abort(404);
        }

        return $next($request);
    }
}
