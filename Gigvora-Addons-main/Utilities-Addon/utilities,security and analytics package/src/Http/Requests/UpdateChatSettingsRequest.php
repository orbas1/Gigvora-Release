<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChatSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'theme' => ['sometimes', 'nullable', 'string', 'max:50'],
            'mute' => ['sometimes', 'boolean'],
            'notifications' => ['sometimes', 'boolean'],
            'privacy' => ['sometimes', 'nullable', 'string', 'in:everyone,connections,none'],
            'allow_requests' => ['sometimes', 'boolean'],
            'background' => ['sometimes', 'nullable', 'string', 'max:100'],
            'presence' => ['sometimes', 'nullable', 'string', 'in:online,away,offline'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'theme' => $this->sanitizeString($this->input('theme')),
            'privacy' => $this->sanitizeString($this->input('privacy')),
            'background' => $this->sanitizeString($this->input('background')),
            'presence' => $this->sanitizeString($this->input('presence')),
        ]);
    }

    private function sanitizeString(?string $value): ?string
    {
        return $value !== null ? trim($value) : null;
    }
}
