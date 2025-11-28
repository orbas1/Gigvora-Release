<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Http\Requests\Headhunters;

use Illuminate\Foundation\Http\FormRequest;

class HeadhunterProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bio' => ['nullable', 'string'],
            'industries' => ['array'],
            'skills' => ['array'],
        ];
    }
}
