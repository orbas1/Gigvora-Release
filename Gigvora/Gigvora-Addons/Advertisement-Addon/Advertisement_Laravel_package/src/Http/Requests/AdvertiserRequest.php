<?php

namespace Advertisement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdvertiserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'billing_email' => 'required|email',
            'daily_spend_limit' => 'nullable|numeric|min:0',
            'lifetime_spend_limit' => 'nullable|numeric|min:0',
            'wallet_balance' => 'nullable|numeric|min:0',
            'status' => 'required|string',
        ];
    }
}
