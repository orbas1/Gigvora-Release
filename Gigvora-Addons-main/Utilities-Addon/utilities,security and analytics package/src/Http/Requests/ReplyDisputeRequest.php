<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReplyDisputeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'message' => ['required', 'string', 'max:1000'],
            'attachments' => ['sometimes', 'array'],
            'attachments.*' => ['string', 'max:255'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'message' => $this->sanitizeString($this->input('message')),
            'attachments' => $this->sanitizeArray($this->input('attachments', [])),
        ]);
    }

    private function sanitizeString(?string $value): ?string
    {
        return $value !== null ? trim($value) : null;
    }

    private function sanitizeArray($value): array
    {
        return collect($value ?? [])
            ->filter(fn ($item) => $item !== null && $item !== '')
            ->map(fn ($item) => is_string($item) ? trim($item) : $item)
            ->values()
            ->all();
    }
}
