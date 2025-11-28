<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Http\Requests\Headhunters;

use Illuminate\Foundation\Http\FormRequest;

class CandidateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string'],
            'skills' => ['array'],
            'experience' => ['array'],
        ];
    }
}
