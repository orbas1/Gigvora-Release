<?php

namespace Jobs\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobRequest extends FormRequest
{
    public function authorize(): bool
    {
        if (! auth()->check()) {
            return false;
        }

        $job = $this->route('job');
        if ($job) {
            return auth()->user()->can('manage', $job);
        }

        $allowedRoles = (array) config('jobs.roles.employer_access', []);
        return in_array(auth()->user()->user_role, $allowedRoles, true) || auth()->user()->can('access_admin_panel');
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
