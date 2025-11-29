<?php

namespace App\Http\Requests\Accounts;

use App\Models\UserWallet;
use Illuminate\Foundation\Http\FormRequest;

class WithdrawRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = getUserRole();
        $profile_id = $user['profileId'];
        $wallet = UserWallet::select('id', 'amount')->where('profile_id', $profile_id)->first();

        return [
            'amount' => ['required', 'numeric', 'min:0', function ($attribute, $value, $fail) use ($wallet){
                if (is_null($wallet) || !isset($wallet->amount)) {
                    $fail('Unable to retrieve wallet balance.');
                } elseif ($value > $wallet->amount) {
                    $fail('Insufficient balance. Your balance is ' .priceFormat($wallet?->amount ?? 0). '.');
                }
            }],
            'payout_type'   => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'required'  => __('general.required_field'),
        ];
    }
}
