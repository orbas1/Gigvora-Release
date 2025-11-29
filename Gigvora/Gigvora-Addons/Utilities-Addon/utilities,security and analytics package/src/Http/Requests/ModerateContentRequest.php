<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModerateContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('moderate') ?? false;
    }

    public function rules(): array
    {
        return [
            'queue_id' => ['required', 'integer', 'exists:pro_network_moderation_queue,id'],
            'action' => ['required', 'string', 'in:approve,hide,block'],
            'notes' => ['sometimes', 'nullable', 'string', 'max:500'],
            'flags' => ['sometimes', 'array'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'action' => $this->sanitizeString($this->input('action')),
            'notes' => $this->sanitizeString($this->input('notes')),
        ]);
    }

    private function sanitizeString(?string $value): ?string
    {
        return $value !== null ? trim($value) : null;
    }
}
