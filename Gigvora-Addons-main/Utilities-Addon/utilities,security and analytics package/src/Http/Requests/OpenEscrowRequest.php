<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpenEscrowRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['sometimes', 'string', 'size:3'],
            'delivery_method' => ['sometimes', 'string', 'in:delivery,collection'],
            'delivery_notes' => ['sometimes', 'nullable', 'string', 'max:500'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'currency' => $this->sanitizeString($this->input('currency')),
            'delivery_method' => $this->sanitizeString($this->input('delivery_method')),
            'delivery_notes' => $this->sanitizeString($this->input('delivery_notes')),
        ]);
    }

    private function sanitizeString(?string $value): ?string
    {
        return $value !== null ? trim($value) : null;
    }
}
