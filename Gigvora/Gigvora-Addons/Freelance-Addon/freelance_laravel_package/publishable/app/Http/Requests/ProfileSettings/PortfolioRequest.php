<?php

namespace App\Http\Requests\ProfileSettings;

use Illuminate\Foundation\Http\FormRequest;

class PortfolioRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'   => 'required',
            'url'     => 'required|url',
        ];
    }

    public function webRules(): array
    {
        return collect($this->rules())->mapWithKeys(fn ($rule, $key) => ['portfolio.'.$key => $rule])->toArray();
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void {
        $data = [];

        $data['title'] = sanitizeTextField($this->title);
        $data['url'] = sanitizeTextField($this->url);
        $data['description'] = sanitizeTextField($this->description);

        $this->merge($data);
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required'      => __('general.required_field'),
            'url'           => __('general.invalid_url'),
        ];
    }

}
