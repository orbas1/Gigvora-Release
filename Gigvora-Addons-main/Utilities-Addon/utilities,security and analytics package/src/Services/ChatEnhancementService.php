<?php

namespace ProNetwork\Services;

use Illuminate\Support\Facades\Event;

class ChatEnhancementService
{
    public function allowAttachments(): bool
    {
        return (bool) config('pro_network_utilities_security_analytics.features.chat_enhancements');
    }

    public function deleteConversation(int $conversationId): void
    {
        Event::dispatch('pro-network.chat.deleted', ['conversation_id' => $conversationId]);
    }

    public function clearConversation(int $conversationId): void
    {
        Event::dispatch('pro-network.chat.cleared', ['conversation_id' => $conversationId]);
    }

    public function recordPresence(int $userId, string $status): void
    {
        Event::dispatch('pro-network.chat.presence', ['user_id' => $userId, 'status' => $status]);
    }
}
