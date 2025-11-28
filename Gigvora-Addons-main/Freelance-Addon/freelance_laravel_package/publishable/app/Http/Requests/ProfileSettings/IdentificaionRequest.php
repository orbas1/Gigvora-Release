<?php

namespace App\Http\Requests\ProfileSettings;

use App\Traits\ApiResponser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class IdentificaionRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'            => 'required',
            'contact_no'      => 'required',
            'identity_no'     => 'required',
            'address'         => 'required',
            'attachments.*'     => 'sometimes|mimes:'.setting('_general.file_ext') ?? ''.'|max:'.( setting('_general.file_size') ?? 1) *1024,
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void {
        $data = [];

        $data['name'] = sanitizeTextField($this->name);
        $data['contact_no'] = sanitizeTextField($this->contact_no);
        $data['identity_no'] = sanitizeTextField($this->identity_no);
        $data['address'] = sanitizeTextField($this->address);

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
            'required' => __('general.required_field')
        ];
    }
}
