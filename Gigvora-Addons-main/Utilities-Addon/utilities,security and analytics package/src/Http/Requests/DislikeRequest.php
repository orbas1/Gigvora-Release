<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DislikeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'reactable_type' => ['required', 'string'],
            'reactable_id' => ['required', 'integer'],
            'reason' => ['nullable', 'string', 'max:500'],
        ];
    }
}
