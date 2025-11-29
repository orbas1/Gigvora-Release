<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Http\Requests\Headhunters;

use Illuminate\Foundation\Http\FormRequest;

class InterviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'scheduled_at' => ['required', 'date'],
            'summary' => ['nullable', 'string'],
        ];
    }
}
