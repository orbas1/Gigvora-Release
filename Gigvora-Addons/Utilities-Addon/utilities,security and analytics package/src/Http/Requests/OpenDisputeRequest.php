<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpenDisputeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'reason' => ['required', 'string', 'max:500'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'reason' => $this->sanitizeString($this->input('reason')),
        ]);
    }

    private function sanitizeString(?string $value): ?string
    {
        return $value !== null ? trim($value) : null;
    }
}
