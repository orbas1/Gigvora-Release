<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Http\Requests\Launchpad;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'motivation' => ['nullable', 'string'],
        ];
    }
}
