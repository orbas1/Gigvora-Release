<?php

namespace Advertisement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreativeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'campaign_id' => 'required|integer|exists:campaigns,id',
            'ad_group_id' => 'nullable|integer|exists:ad_groups,id',
            'type' => 'required|string|in:text,banner,video,search,recommendation',
            'title' => 'required|string|max:255',
            'body' => 'nullable|string',
            'destination_url' => 'nullable|url',
            'media_path' => 'nullable|string',
            'status' => 'required|string',
            'cta' => 'nullable|string|max:50',
        ];
    }
}
