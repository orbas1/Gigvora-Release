<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResharePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'post_id' => ['required', 'integer'],
            'source_post_id' => ['required', 'integer'],
            'comment' => ['nullable', 'string', 'max:500'],
            'audience' => ['nullable', 'string', 'max:50'],
        ];
    }
}
