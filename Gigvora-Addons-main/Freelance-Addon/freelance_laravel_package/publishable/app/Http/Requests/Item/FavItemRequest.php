<?php

namespace App\Http\Requests\Item;

use Illuminate\Foundation\Http\FormRequest;

class FavItemRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'corresponding_id' => 'required',
            'type' => 'required|in:profile,gig,project',
        ];
    }

     /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => __('general.required_field'),
        ];
    }
}
