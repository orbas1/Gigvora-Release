<?php

namespace App\Http\Requests;

use App\Rules\OverflowRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ProposalStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'proposal_amount'       => ['required','numeric','gt:1', new OverflowRule(0,99999999)], 
            'special_comments'      => 'required|string',
            'payout_type'           => 'required|in:fixed,hourly,milestone', 
            'milestones'            => 'required_if:payout_type,milestone|array|min:1'
        ];
    }


    public function after()  {
        return [
            function (Validator $validator) {
                if( $this->input('payout_type') == 'milestone') {
                    $milestones = $this->input('milestones');
                    $proposalAmount = $this->input('proposal_amount');
            
                    $totalPrice = array_sum(array_column($milestones, 'price'));

                    if ($totalPrice > $proposalAmount) {
                        return $validator->errors()->add(
                            'milestones',
                            __('proposal.price_error')
                        ); 
                    }
                }
            }
        ];
    }
}
