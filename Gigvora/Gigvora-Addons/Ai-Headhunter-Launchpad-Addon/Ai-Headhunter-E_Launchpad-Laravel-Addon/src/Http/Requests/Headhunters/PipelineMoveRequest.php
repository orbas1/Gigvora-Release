<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Http\Requests\Headhunters;

use Illuminate\Foundation\Http\FormRequest;

class PipelineMoveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'candidate_id' => ['sometimes', 'integer'],
            'stage' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
