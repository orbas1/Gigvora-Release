<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Http\Requests\AiWorkspace;

use Illuminate\Foundation\Http\FormRequest;

class AiToolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'prompt' => ['nullable', 'string'],
            'profile_summary' => ['sometimes', 'string'],
            'audience' => ['sometimes', 'string'],
            'tone' => ['sometimes', 'string'],
            'niche' => ['sometimes', 'string'],
            'question' => ['sometimes', 'string'],
            'content' => ['sometimes', 'string'],
            'role' => ['sometimes', 'string'],
            'topic' => ['sometimes', 'string'],
            'product' => ['sometimes', 'string'],
        ];
    }
}
