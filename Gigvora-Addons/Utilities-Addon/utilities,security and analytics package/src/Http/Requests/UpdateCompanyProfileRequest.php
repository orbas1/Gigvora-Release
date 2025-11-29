<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use ProNetwork\Models\CompanyProfile;

class UpdateCompanyProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $company = $this->route('company');
        $companyProfile = $company instanceof CompanyProfile ? $company : CompanyProfile::where('page_id', $company)->first();

        return $user !== null && ($companyProfile ? $user->can('update', $companyProfile) : $user->can('update', CompanyProfile::class));
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'website' => ['nullable', 'url', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'industries' => ['sometimes', 'array'],
            'industries.*' => ['string', 'max:100'],
            'tags' => ['sometimes', 'array'],
            'tags.*' => ['string', 'max:100'],
            'is_hiring' => ['sometimes', 'boolean'],
            'is_remote_friendly' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => $this->sanitizeString($this->input('name')),
            'description' => $this->sanitizeString($this->input('description')),
            'website' => $this->sanitizeString($this->input('website')),
            'location' => $this->sanitizeString($this->input('location')),
            'industries' => $this->sanitizeArray($this->input('industries', [])),
            'tags' => $this->sanitizeArray($this->input('tags', [])),
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
}
