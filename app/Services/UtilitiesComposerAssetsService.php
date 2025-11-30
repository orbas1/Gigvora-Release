<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use ProNetwork\Support\Enums\ReactionType;

class UtilitiesComposerAssetsService
{
    public function payload(): array
    {
        return [
            'reactions' => $this->reactions(),
            'emoji_packs' => $this->emojiPacks(),
            'gif' => $this->gifConfig(),
            'sticker_packs' => $this->stickerPacks(),
        ];
    }

    public function reactions(): array
    {
        return collect(ReactionType::cases())
            ->map(fn (ReactionType $type) => [
                'type' => $type->value,
                'label' => $this->reactionLabel($type),
                'emoji' => $this->reactionEmoji($type),
                'icon' => $this->reactionIcon($type),
                'color' => $this->reactionColor($type),
            ])
            ->values()
            ->all();
    }

    public function emojiPacks(): array
    {
        return Arr::get(config('utilities.composer', []), 'emoji_packs', []);
    }

    public function stickerPacks(): array
    {
        return Arr::get(config('utilities.composer', []), 'sticker_packs', []);
    }

    public function gifConfig(): array
    {
        $config = Arr::get(config('utilities.composer', []), 'gif', []);
        $enabled = (bool) ($config['enabled'] ?? false);

        return [
            'enabled' => $enabled,
            'provider' => $config['provider'] ?? 'tenor',
            'limit' => (int) ($config['limit'] ?? 12),
            'endpoint' => $enabled && ! empty($config['api_key'])
                ? route('api.utilities.composer.gifs')
                : null,
        ];
    }

    protected function reactionLabel(ReactionType $type): string
    {
        return match ($type) {
            ReactionType::LIKE => get_phrase('Like'),
            ReactionType::LOVE => get_phrase('Love'),
            ReactionType::CELEBRATE => get_phrase('Celebrate'),
            ReactionType::INSIGHTFUL => get_phrase('Insightful'),
            ReactionType::SUPPORT => get_phrase('Support'),
            ReactionType::CURIOUS => get_phrase('Curious'),
            ReactionType::DISLIKE => get_phrase('Dislike'),
        };
    }

    protected function reactionEmoji(ReactionType $type): string
    {
        return match ($type) {
            ReactionType::LIKE => '👍',
            ReactionType::LOVE => '❤️',
            ReactionType::CELEBRATE => '🎉',
            ReactionType::INSIGHTFUL => '💡',
            ReactionType::SUPPORT => '🙌',
            ReactionType::CURIOUS => '🤔',
            ReactionType::DISLIKE => '👎',
        };
    }

    protected function reactionIcon(ReactionType $type): string
    {
        return match ($type) {
            ReactionType::LIKE => 'fa-regular fa-thumbs-up',
            ReactionType::LOVE => 'fa-regular fa-heart',
            ReactionType::CELEBRATE => 'fa-solid fa-champagne-glasses',
            ReactionType::INSIGHTFUL => 'fa-regular fa-lightbulb',
            ReactionType::SUPPORT => 'fa-regular fa-handshake',
            ReactionType::CURIOUS => 'fa-regular fa-circle-question',
            ReactionType::DISLIKE => 'fa-regular fa-thumbs-down',
        };
    }

    protected function reactionColor(ReactionType $type): string
    {
        return match ($type) {
            ReactionType::LIKE => 'primary',
            ReactionType::LOVE => 'rose',
            ReactionType::CELEBRATE => 'amber',
            ReactionType::INSIGHTFUL => 'sky',
            ReactionType::SUPPORT => 'emerald',
            ReactionType::CURIOUS => 'violet',
            ReactionType::DISLIKE => 'slate',
        };
    }
}

