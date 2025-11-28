<?php

namespace ProNetwork\Services;

use ProNetwork\Models\PostEnhancement;

class PostEnhancementService
{
    public function saveEnhancement(int $postId, string $type, array $payload = []): PostEnhancement
    {
        return PostEnhancement::updateOrCreate(
            ['post_id' => $postId],
            [
                'type' => $type,
                'payload' => $payload,
            ]
        );
    }

    public function remove(int $postId): void
    {
        PostEnhancement::where('post_id', $postId)->delete();
    }
}
