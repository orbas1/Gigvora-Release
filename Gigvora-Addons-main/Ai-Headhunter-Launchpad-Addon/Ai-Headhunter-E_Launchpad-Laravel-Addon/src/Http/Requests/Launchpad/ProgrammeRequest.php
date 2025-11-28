<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Http\Requests\Launchpad;

use Illuminate\Foundation\Http\FormRequest;

class ProgrammeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'category' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'estimated_hours' => ['nullable', 'integer'],
            'estimated_weeks' => ['nullable', 'integer'],
            'reference_offered' => ['boolean'],
            'qualification_offered' => ['boolean'],
            'pay_reduction_percentage' => ['nullable', 'numeric'],
            'tasks' => ['array'],
            'tasks.*.title' => ['required_with:tasks', 'string'],
            'tasks.*.description' => ['nullable', 'string'],
            'tasks.*.estimated_hours' => ['nullable', 'integer'],
        ];
    }
}
