<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\AiWorkspace\Services;

use App\Models\User;
use Gigvora\TalentAi\Domain\AiWorkspace\Models\AiByokCredential;

class AiProviderService
{
    public function callProvider(string $toolSlug, array $payload, User $user, ?AiByokCredential $credential = null): array
    {
        $provider = config('gigvora_talent_ai.ai.provider');

        return [
            'provider' => $provider,
            'tool' => $toolSlug,
            'message' => 'AI provider call executed with placeholder response.',
            'input' => $payload,
            'used_byok' => (bool) $credential,
            'user_id' => $user->id,
        ];
    }
}
