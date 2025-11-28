<?php

namespace Jobs\Http\Requests;

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
