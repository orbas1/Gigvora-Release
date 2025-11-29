<?php

namespace Jobs\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'workplace_type' => ['nullable', 'string', 'max:100'],
            'employment_type' => ['nullable', 'string', 'max:100'],
            'salary_min' => ['nullable', 'numeric'],
            'salary_max' => ['nullable', 'numeric'],
            'currency' => ['nullable', 'string', 'max:3'],
            'status' => ['nullable', 'string', 'max:50'],
            'published_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date'],
            'is_featured' => ['boolean'],
        ];
    }
}
