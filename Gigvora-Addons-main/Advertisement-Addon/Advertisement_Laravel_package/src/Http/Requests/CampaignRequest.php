<?php

namespace Advertisement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'advertiser_id' => 'required|integer|exists:advertisers,id',
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'budget' => 'required|numeric|min:' . config('advertisement.minimum_budget'),
            'bidding' => 'required|string|in:click,view,conversion',
            'placement' => 'required|string|in:' . implode(',', config('advertisement.placements')),
            'objective' => 'nullable|string',
            'status' => 'nullable|string',
        ];
    }
}
