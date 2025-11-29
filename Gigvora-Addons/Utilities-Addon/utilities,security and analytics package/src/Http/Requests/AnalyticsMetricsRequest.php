<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnalyticsMetricsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('viewAnalytics') ?? false;
    }

    public function rules(): array
    {
        return [
            'entity_type' => ['sometimes', 'nullable', 'string', 'max:100'],
            'entity_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'metrics' => ['sometimes', 'array'],
            'metrics.*' => ['string', 'max:100'],
            'from' => ['sometimes', 'date'],
            'to' => ['sometimes', 'date', 'after_or_equal:from'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'entity_type' => $this->sanitizeString($this->input('entity_type')),
        ]);
    }

    private function sanitizeString(?string $value): ?string
    {
        return $value !== null ? trim($value) : null;
    }
}
