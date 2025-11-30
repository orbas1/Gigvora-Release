@php
    $reactionOptions = $reactionOptions ?? collect(app(\App\Services\UtilitiesComposerAssetsService::class)->reactions() ?? [])->keyBy('type');
    $reactions = $message->reactions ?? collect();
    $userReaction = $reactions->firstWhere('user_id', auth()->user()->id);
    $userReactionType = $userReaction
        ? ($userReaction->type instanceof \BackedEnum ? $userReaction->type->value : $userReaction->type)
        : null;
@endphp

<div class="gv-chat-reaction-picker">
    @foreach($reactionOptions as $option)
        <button type="button"
            class="gv-chat-reaction-button {{ $userReactionType === $option['type'] ? 'is-active' : '' }}"
            onclick="myMessageReact('{{ $option['type'] }}', 'update', {{ $message->id }})">
            <span class="gv-chat-reaction-button__emoji">{{ $option['emoji'] }}</span>
            <span class="gv-chat-reaction-button__label">{{ $option['label'] }}</span>
        </button>
    @endforeach
</div>

