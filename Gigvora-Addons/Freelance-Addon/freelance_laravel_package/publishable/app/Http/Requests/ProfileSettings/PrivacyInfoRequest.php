<?php

namespace App\Http\Requests\ProfileSettings;

use Illuminate\Foundation\Http\FormRequest;

class PrivacyInfoRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [ 'show_image' => 'required|boolean'];
    }
}
