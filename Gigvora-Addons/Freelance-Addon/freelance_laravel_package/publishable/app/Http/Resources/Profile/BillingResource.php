<?php

namespace App\Http\Resources\Profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BillingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'first_name' => $this?->billing_first_name ?? '',
            'last_name' => $this?->billing_last_name  ?? '',
            'company' => $this?->billing_company  ?? '',
            'postal_code' => $this?->billing_postal_code  ?? '',
            'email' => $this?->billing_email  ?? '',
            'phone' => $this?->billing_phone  ?? '',
            'address' => $this?->billing_address  ?? '',
            'city' => $this?->billing_city  ?? '',
            'country_id' => $this?->country_id  ?? '',
            'state_id' => $this?->state_id  ?? ''
        ];
    }
}
