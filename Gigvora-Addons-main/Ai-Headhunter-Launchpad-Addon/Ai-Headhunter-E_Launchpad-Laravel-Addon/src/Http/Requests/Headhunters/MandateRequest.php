<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Http\Requests\Headhunters;

use Illuminate\Foundation\Http\FormRequest;

class MandateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'location' => ['nullable', 'string'],
            'fee_model' => ['nullable', 'string'],
            'fee_amount' => ['nullable', 'numeric'],
            'requirements' => ['array'],
        ];
    }
}
