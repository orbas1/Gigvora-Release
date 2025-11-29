<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Http\Requests\Volunteering;

use Illuminate\Foundation\Http\FormRequest;

class OpportunityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'sector' => ['required', 'string'],
            'location' => ['nullable', 'string'],
            'commitment' => ['nullable', 'string'],
            'expenses_covered' => ['boolean'],
            'verified' => ['boolean'],
            'status' => ['sometimes', 'string'],
            'description' => ['nullable', 'string'],
        ];
    }
}
