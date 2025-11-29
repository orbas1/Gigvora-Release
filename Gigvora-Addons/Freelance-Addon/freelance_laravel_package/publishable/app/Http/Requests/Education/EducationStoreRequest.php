<?php

namespace App\Http\Requests\Education;

use Illuminate\Foundation\Http\FormRequest;

class EducationStoreRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'deg_title'             => 'required|string|max:255',
            'deg_institue_name'     => 'nullable',
            'address'               => 'required',
            'deg_description'       => 'nullable',
            'deg_start_date'        => 'required|date',
            'deg_end_date'          => 'nullable|required_if:is_ongoing,0,false,null|date|after:deg_start_date'
        ];
    }

    public function webRules(): array
    {
        return collect($this->rules())->mapWithKeys(function ($rule, $key) {
            if ($key === 'deg_end_date') {
                return [
                    'education_detail.deg_end_date' => 'nullable|required_if:education_detail.is_ongoing,0,false,null|date|after:education_detail.deg_start_date'
                ];
            } else{
                return ['education_detail.'.$key => $rule];
            }
           
        })->toArray();
    }


    public function messages(): array
    {
        return [
            'required'      => __('general.required_field'),
            'required_if'   => __('general.required_field'),
        ];
    }

    public function prepareForValidation(): void
    {
        
        $data = [];
        if($this->expectsJson() || $this->is('api/*')){
            $data['deg_title']          = sanitizeTextField( $this->deg_title );
            $data['deg_institue_name']  = sanitizeTextField( $this->deg_institue_name );
            $data['address']            = sanitizeTextField( $this->address );
            $data['deg_description']    = sanitizeTextField( $this->deg_description );
        } else{
            $data['education_detail']['deg_title']          = sanitizeTextField( $this->education_detail['deg_title'] );
            $data['education_detail']['deg_institue_name']  = sanitizeTextField( $this->education_detail['deg_institue_name'] );
            $data['education_detail']['address']            = sanitizeTextField( $this->education_detail['address'] );
            $data['education_detail']['deg_description']    = sanitizeTextField( $this->education_detail['deg_description'] );
        }

        $this->merge($this->all(), $data);
    }
}
