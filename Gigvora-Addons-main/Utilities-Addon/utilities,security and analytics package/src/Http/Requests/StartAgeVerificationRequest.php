<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StartAgeVerificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'document_type' => ['sometimes', 'nullable', 'string', 'max:50'],
            'redirect_url' => ['sometimes', 'nullable', 'url'],
            'metadata' => ['sometimes', 'array'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'document_type' => $this->sanitizeString($this->input('document_type')),
            'redirect_url' => $this->sanitizeString($this->input('redirect_url')),
        ]);
    }

    private function sanitizeString(?string $value): ?string
    {
        return $value !== null ? trim($value) : null;
    }
}
