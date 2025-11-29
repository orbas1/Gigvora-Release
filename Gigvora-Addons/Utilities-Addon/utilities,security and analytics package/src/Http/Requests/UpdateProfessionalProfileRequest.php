<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use ProNetwork\Models\ProfessionalProfile;

class UpdateProfessionalProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return $user !== null && ($user->can('update', ProfessionalProfile::class) || $user->id === (int) ($this->route('user') ?? $user->id));
    }

    public function rules(): array
    {
        return [
            'headline' => ['nullable', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'top_skills' => ['sometimes', 'array'],
            'top_skills.*' => ['string', 'max:100'],
            'skills' => ['sometimes', 'array'],
            'skills.*' => ['string', 'max:100'],
            'experience' => ['sometimes', 'array'],
            'experience.*.company' => ['required_with:experience', 'string', 'max:255'],
            'experience.*.role' => ['nullable', 'string', 'max:255'],
            'experience.*.started_at' => ['nullable', 'date'],
            'experience.*.ended_at' => ['nullable', 'date'],
            'education' => ['sometimes', 'array'],
            'education.*.institution' => ['required_with:education', 'string', 'max:255'],
            'education.*.qualification' => ['nullable', 'string', 'max:255'],
            'education.*.started_at' => ['nullable', 'date'],
            'education.*.ended_at' => ['nullable', 'date'],
            'certifications' => ['sometimes', 'array'],
            'certifications.*.name' => ['required_with:certifications', 'string', 'max:255'],
            'certifications.*.authority' => ['nullable', 'string', 'max:255'],
            'certifications.*.issued_at' => ['nullable', 'date'],
            'references' => ['sometimes', 'array'],
            'references.*.name' => ['required_with:references', 'string', 'max:255'],
            'references.*.relationship' => ['nullable', 'string', 'max:255'],
            'references.*.contact' => ['nullable', 'string', 'max:255'],
            'dbs_cleared' => ['sometimes', 'boolean'],
            'available_for_work' => ['sometimes', 'boolean'],
            'interests' => ['sometimes', 'array'],
            'interests.*' => ['string', 'max:100'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'headline' => $this->sanitizeString($this->input('headline')),
            'tagline' => $this->sanitizeString($this->input('tagline')),
            'location' => $this->sanitizeString($this->input('location')),
            'skills' => $this->sanitizeArray($this->input('skills', [])),
            'top_skills' => $this->sanitizeArray($this->input('top_skills', [])),
            'experience' => $this->sanitizeArrayOfArrays($this->input('experience', [])),
            'education' => $this->sanitizeArrayOfArrays($this->input('education', [])),
            'certifications' => $this->sanitizeArrayOfArrays($this->input('certifications', [])),
            'references' => $this->sanitizeArrayOfArrays($this->input('references', [])),
            'interests' => $this->sanitizeArray($this->input('interests', [])),
        ]);
    }

    private function sanitizeString(?string $value): ?string
    {
        return $value !== null ? trim($value) : null;
    }

    private function sanitizeArray($value): array
    {
        return collect($value ?? [])
            ->filter(fn ($item) => $item !== null && $item !== '')
            ->map(fn ($item) => is_string($item) ? trim($item) : $item)
            ->values()
            ->all();
    }

    private function sanitizeArrayOfArrays($value): array
    {
        return collect($value ?? [])
            ->filter(fn ($item) => is_array($item))
            ->map(function (array $item) {
                return collect($item)
                    ->map(fn ($val) => is_string($val) ? trim($val) : $val)
                    ->all();
            })
            ->values()
            ->all();
    }
}
