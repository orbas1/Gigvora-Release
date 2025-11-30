<?php

namespace Jobs\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        if (! auth()->check()) {
            return false;
        }

        $user = auth()->user();
        $allowedRoles = (array) config('jobs.roles.employer_access', []);

        if (in_array($user->user_role, $allowedRoles, true) || $user->can('access_admin_panel')) {
            return true;
        }

        return (int) $this->input('candidate_id') === (int) $user->id;
    }

    public function rules(): array
    {
        return [
            'job_id' => ['required', 'integer'],
            'candidate_id' => ['required', 'integer'],
            'cover_letter_id' => ['nullable', 'integer'],
            'cv_template_id' => ['nullable', 'integer'],
            'resume_path' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'max:50'],
            'answers' => ['sometimes', 'array'],
            'answers.*.screening_question_id' => ['nullable', 'integer'],
            'answers.*.answer' => ['nullable'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $job = $this->route('job');

        if ($job) {
            $this->merge(['job_id' => $job instanceof \Illuminate\Database\Eloquent\Model ? $job->getKey() : $job]);
        }
    }
}
