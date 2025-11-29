<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use ProNetwork\Support\Enums\ReactionType;

class ReactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $allowed = collect(ReactionType::cases())
            ->reject(fn (ReactionType $type) => $type === ReactionType::DISLIKE)
            ->map(fn (ReactionType $type) => $type->value)
            ->all();

        return [
            'reactable_type' => ['required', 'string'],
            'reactable_id' => ['required', 'integer'],
            'type' => ['required', 'string', Rule::in($allowed)],
        ];
    }
}
