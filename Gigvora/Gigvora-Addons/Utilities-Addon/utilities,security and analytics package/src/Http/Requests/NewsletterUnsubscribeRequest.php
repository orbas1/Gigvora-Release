<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsletterUnsubscribeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:191'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => $this->sanitizeString($this->input('email')),
        ]);
    }

    private function sanitizeString(?string $value): ?string
    {
        return $value !== null ? trim($value) : null;
    }
}
