<?php

namespace App\Http\Requests\ProfileSettings;

use App\Traits\ApiResponser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProfileSettingsRequest extends FormRequest
{

    use ApiResponser;

    public function failedValidation(Validator $validator) {

        throw new HttpResponseException($this->validation('Validation errors', $validator->errors()));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = getUserRole();
        $user_role = $user['roleName'] ?? '';
        $validations = [
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'tagline' => 'required|string|min:10',
            'country' => 'required',
            'description' => 'required',
        ];

        if( $user_role == 'seller' ){
            $validations['languages'] = 'required|array|min:1';
            $validations['skills'] = 'required|array|min:1';
            $validations['hourly_rate'] = 'required|numeric';
            $validations['seller_type'] = 'required';
            $validations['english_level'] = 'required';
        }

        return $validations;
    }
}
