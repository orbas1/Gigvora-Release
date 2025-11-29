<?php

namespace App\Http\Requests\Accounts;

use Illuminate\Foundation\Http\FormRequest;

class PayoutMethodRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => 'required|in:escrow,paypal,payoneer,bank',
            'escrow_email' => 'nullable|required_if:type,escrow|email',
            'escrow_api_key' => 'required_if:type,escrow',
            'paypal_email' => 'nullable|required_if:type,paypal|email',
            'payoneer_email' => 'nullable|required_if:type,payoneer|email',
            'title' => 'required_if:type,bank',
            'account_number' => 'required_if:type,bank',
            'bank_name' => 'required_if:type,bank',
            'routing_number' => 'required_if:type,bank',
            'bank_iban' => 'required_if:type,bank',
            'bank_bic_swift' => 'required_if:type,bank',
        ];
    }

    public function webRules(): array
    {
        $bank_keys = ['title','account_number','bank_name','routing_number','bank_iban','bank_bic_swift'];
        return collect($this->rules())->mapWithKeys(function ($rule, $key) use ($bank_keys){
            $option_key = in_array($key, $bank_keys) ? 'bankAccountInfo.'.$key : $key;
            return [ $option_key => $rule];
        })->toArray();
    }

    public function messages(): array
    {
        return [
            'required'  => __('general.required_field'),
            'required_if' => __('general.required_field'),
            "email" => __('general.invalid_email'),
        ];
    }

    public function prepareForValidation(): void
    {
        $data = [];

        $data['type'] = sanitizeTextField( $this->type );
        $data['escrow_email'] = sanitizeTextField( $this->escrow_email );
        $data['escrow_api_key'] = sanitizeTextField( $this->escrow_api_key );
        $data['paypal_email'] = sanitizeTextField( $this->paypal_email );
        $data['payoneer_email'] = sanitizeTextField( $this->payoneer_email );
        $data['title'] = sanitizeTextField( $this->title );
        $data['account_number'] = sanitizeTextField( $this->account_number );
        $data['bank_name'] = sanitizeTextField( $this->bank_name );
        $data['routing_number'] = sanitizeTextField( $this->routing_number );
        $data['bank_iban'] = sanitizeTextField( $this->bank_iban );
        $data['bank_bic_swift'] = sanitizeTextField( $this->bank_bic_swift );

        $this->merge($data);
    }
}
