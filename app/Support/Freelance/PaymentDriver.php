<?php

namespace Amentotech\LaraPayEase\Facades;

class PaymentDriver
{
    /**
     * Return the list of configured gateways.
     */
    public static function supportedGateways(): array
    {
        return config('freelance.payments.gateways', []);
    }

    /**
     * Return the supported currency definitions keyed by ISO code.
     */
    public static function supportedCurrencies(): array
    {
        return config('freelance.payments.currencies', []);
    }

    /**
     * Resolve the IPN route name for the provided gateway key.
     */
    public static function getIpnUrl(?string $gateway): ?string
    {
        if (! $gateway) {
            return null;
        }

        return data_get(self::supportedGateways(), "{$gateway}.ipn_route");
    }
}

