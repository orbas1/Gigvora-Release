<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModerationQueueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('moderate') ?? false;
    }

    public function rules(): array
    {
        return [
            'status' => ['sometimes', 'string', 'in:pending,resolved,approve,hide,block'],
            'reason' => ['sometimes', 'nullable', 'string', 'max:200'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'status' => $this->sanitizeString($this->input('status')),
            'reason' => $this->sanitizeString($this->input('reason')),
            'per_page' => $this->input('per_page', 15),
        ]);
    }

    private function sanitizeString(?string $value): ?string
    {
        return $value !== null ? trim($value) : null;
    }
}
