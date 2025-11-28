<?php

namespace App\Http\Requests\ProfileSettings;

use App\Models\CountryState;
use Illuminate\Foundation\Http\FormRequest;

class BillingInfoRequest extends FormRequest
{
     /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'company' => 'sometimes',
            'country_id' => 'required',
            'city' => 'required',
            'postal_code' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
        ];

        if ($this->has('country_has_state')) {
            $rules['state_id'] = 'required';
        }

        return $rules;
    }

    public function webRules(): array
    {
        return collect($this->rules())->mapWithKeys(fn ($rule, $key) => ['billing_info.'.$key => $rule])->toArray();
    }

    public function messages(): array
    {
        return [
            'required'      => __('general.required_field'),
            'email'         => __('general.invalid_email')
        ];
    }

     /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void {
        $data = [];
        $data['first_name'] = sanitizeTextField($this->first_name);
        $data['last_name'] = sanitizeTextField($this->last_name);
        $data['postal_code'] = sanitizeTextField($this->postal_code);
        $data['email'] = sanitizeTextField($this->email);
        $data['company'] = sanitizeTextField($this->company);
        $data['phone'] = sanitizeTextField($this->phone);
        $data['city'] = sanitizeTextField($this->city);
        $data['country_id'] = sanitizeTextField($this->country_id);
        $data['address'] = sanitizeTextField($this->address);
        $data['state_id'] = sanitizeTextField($this->state_id);

        if(!empty($data['country_id'])){
            $states = CountryState::where('country_id', $data['country_id'])->select('id','name')->get();
            if(!$states->isEmpty()){
                $data['country_has_state'] = true;
            }
        }

        $this->merge($data);
    }

}
