<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateStoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'story_id' => ['required', 'integer'],
            'overlays' => ['sometimes', 'array'],
            'filters' => ['sometimes', 'array'],
            'stickers' => ['sometimes', 'array'],
            'links' => ['sometimes', 'array'],
            'music_track_id' => ['nullable', 'integer'],
            'live_session_id' => ['nullable', 'integer'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'story_id' => (int) $this->input('story_id'),
        ]);
    }
}
