<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use ProNetwork\Models\MarketplaceDispute;

class ResolveDisputeRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return $user !== null && $user->can('resolve', MarketplaceDispute::class);
    }

    public function rules(): array
    {
        return [
            'resolution' => ['required', 'string', 'max:500'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'resolution' => $this->sanitizeString($this->input('resolution')),
        ]);
    }

    private function sanitizeString(?string $value): ?string
    {
        return $value !== null ? trim($value) : null;
    }
}
