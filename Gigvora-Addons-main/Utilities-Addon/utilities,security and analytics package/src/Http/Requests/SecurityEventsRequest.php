<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SecurityEventsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('viewSecurity') ?? false;
    }

    public function rules(): array
    {
        return [
            'type' => ['sometimes', 'array'],
            'type.*' => ['string'],
            'severity' => ['sometimes', 'string', 'in:info,warning,error,critical'],
            'from' => ['sometimes', 'date'],
            'to' => ['sometimes', 'date', 'after_or_equal:from'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'severity' => $this->sanitizeString($this->input('severity')),
            'per_page' => $this->input('per_page', 15),
        ]);
    }

    private function sanitizeString(?string $value): ?string
    {
        return $value !== null ? trim($value) : null;
    }
}
