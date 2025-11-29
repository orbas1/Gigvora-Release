<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnalyticsSeriesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('viewAnalytics') ?? false;
    }

    public function rules(): array
    {
        return [
            'events' => ['sometimes', 'array'],
            'events.*' => ['string', 'max:150'],
            'from' => ['sometimes', 'date'],
            'to' => ['sometimes', 'date', 'after_or_equal:from'],
        ];
    }
}
