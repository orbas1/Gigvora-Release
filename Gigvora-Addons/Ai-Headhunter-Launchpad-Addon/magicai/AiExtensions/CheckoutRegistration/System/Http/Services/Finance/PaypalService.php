<?php

declare(strict_types=1);

namespace App\Extensions\CheckoutRegistration\System\Http\Services\Finance;

use App\Extensions\CheckoutRegistration\System\Http\Services\Contracts\BaseGatewayService;
use App\Enums\Plan\TypeEnum;
use App\Helpers\Classes\Helper;
use App\Models\Gateways;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalService implements BaseGatewayService
{
    protected string $GATEWAY_CODE = 'paypal';

    protected ?Gateways $gateway;

    protected ?string $key;

    protected ?PayPalClient $client = null;

    public function __construct()
    {
        $this->setGatewaysModel();
        $this->setKey();
    }

    public function createPreReqsIfNeeded(?User $user): void {}

    public function getGatewaysCode(): ?string
    {
        return $this->GATEWAY_CODE;
    }

    public function getGatewaysModel(): ?Gateways
    {
        return $this->gateway;
    }

    public function setGatewaysModel(): void
    {
        $this->gateway = Gateways::where('code', $this->GATEWAY_CODE)->where('is_active', 1)->first();
    }

    public function setKey(): void
    {
        if (is_null($this->gateway)) {
            abort(404, 'PayPal gateway is not defined!');
        }

        $mode = $this->gateway->mode === 'sandbox' ? 'sandbox' : 'live';
        $clientId = $mode === 'sandbox' ? $this->gateway->sandbox_client_id : $this->gateway->live_client_id;
        $clientSecret = $mode === 'sandbox' ? $this->gateway->sandbox_client_secret : $this->gateway->live_client_secret;

        if (! $clientId || ! $clientSecret) {
            abort(422, 'PayPal credentials are missing for the configured mode.');
        }

        config(['paypal.client_id' => $clientId]);
        config(['paypal.secret' => $clientSecret]);
        config(['paypal.settings.mode' => $mode]);

        $this->client = new PayPalClient;
        $this->client->setApiCredentials(config('paypal'));
        $this->client->getAccessToken();
        $this->key = $clientSecret;
    }

    public function createSubscription(Plan $plan, User $user): array
    {
        if (is_null($this->client)) {
            $this->setKey();
        }

        try {
            $currency = Helper::findCurrencyFromId($this->gateway?->currency)->code ?? 'USD';
            $response = $this->client->createOrder([
                'intent'              => 'CAPTURE',
                'purchase_units'      => [[
                    'reference_id' => (string) $plan->id,
                    'description'  => $plan->name,
                    'amount'       => [
                        'currency_code' => $currency,
                        'value'         => number_format($plan->price, 2, '.', ''),
                    ],
                ]],
                'application_context' => [
                    'user_action' => 'PAY_NOW',
                    'brand_name' => config('app.name'),
                ],
            ]);

            return [
                'orderId'    => Arr::get($response, 'id'),
                'approveUrl' => collect(Arr::get($response, 'links', []))
                    ->firstWhere('rel', 'approve')['href'] ?? null,
            ];
        } catch (\Throwable $e) {
            Log::error('paypal->createSubscription(): ' . $e->getMessage());

            return [];
        }
    }

    public function createPrepaid(Plan $plan, User $user): array
    {
        if (is_null($this->client)) {
            $this->setKey();
        }

        try {
            $currency = Helper::findCurrencyFromId($this->gateway?->currency)->code ?? 'USD';
            $response = $this->client->createOrder([
                'intent'              => 'CAPTURE',
                'purchase_units'      => [[
                    'reference_id' => (string) $plan->id,
                    'description'  => $plan->name . ' prepaid',
                    'amount'       => [
                        'currency_code' => $currency,
                        'value'         => number_format($plan->price, 2, '.', ''),
                    ],
                ]],
                'application_context' => [
                    'user_action' => 'PAY_NOW',
                    'brand_name' => config('app.name'),
                ],
            ]);

            return [
                'orderId'    => Arr::get($response, 'id'),
                'approveUrl' => collect(Arr::get($response, 'links', []))
                    ->firstWhere('rel', 'approve')['href'] ?? null,
            ];
        } catch (\Throwable $e) {
            Log::error('paypal->createPrepaid(): ' . $e->getMessage());

            return [];
        }
    }

    public function subscribeCheckout(Request $request, $referral = null): void {}

    public function prepaidCheckout(Request $request, $referral = null): void {}

    public function checkoutData(User $user, ?int $planID): array
    {
        $plan = Plan::findOrFail($planID);

        return $plan->type === TypeEnum::SUBSCRIPTION->value
            ? $this->createSubscription($plan, $user)
            : $this->createPrepaid($plan, $user);
    }
}
