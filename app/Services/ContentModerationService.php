<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Str;

class ContentModerationService
{
    public function reviewAndSanitize(string $text, User $user): array
    {
        $clean = trim(strip_tags($text));
        $normalized = Str::lower($clean);

        $severeMatches = $this->findMatch($normalized, config('moderation.banned_phrases.severe', []));
        if ($severeMatches) {
            $this->applyPermanentBan($user, $severeMatches);

            return [
                'blocked' => true,
                'clean_text' => $clean,
                'reason' => 'severe_violation',
            ];
        }

        $standardMatches = $this->findMatch($normalized, config('moderation.banned_phrases.standard', []));
        if ($standardMatches) {
            $until = $this->applyEscalation($user, $standardMatches);

            return [
                'blocked' => true,
                'clean_text' => $clean,
                'reason' => 'shadow_ban',
                'until' => $until,
            ];
        }

        return [
            'blocked' => false,
            'clean_text' => $clean,
        ];
    }

    protected function findMatch(string $text, array $phrases): ?string
    {
        foreach ($phrases as $phrase) {
            if (Str::contains($text, Str::lower($phrase))) {
                return $phrase;
            }
        }

        return null;
    }

    protected function applyPermanentBan(User $user, string $phrase): void
    {
        $user->forceFill([
            'status' => 'banned',
            'profile_status' => 'banned',
            'shadow_banned_until' => null,
            'moderation_strikes' => max($user->moderation_strikes, 6),
            'banned_reason' => $phrase,
        ])->save();

        AuditLog::create([
            'actor_id' => $user->id,
            'target_type' => User::class,
            'target_id' => $user->id,
            'action' => 'moderation.auto_ban',
            'changes' => ['phrase' => $phrase],
            'source' => 'system',
        ]);
    }

    protected function applyEscalation(User $user, string $phrase): string
    {
        $strikes = $user->moderation_strikes + 1;
        $user->moderation_strikes = $strikes;

        $minutes = config('moderation.escalation_minutes.' . $strikes, 30 * 24 * 60);
        $until = now()->addMinutes($minutes);

        $user->forceFill([
            'shadow_banned_until' => $until,
            'profile_status' => 'shadow_banned',
            'status' => 'restricted',
            'banned_reason' => $phrase,
        ])->save();

        AuditLog::create([
            'actor_id' => $user->id,
            'target_type' => User::class,
            'target_id' => $user->id,
            'action' => 'moderation.shadow_ban',
            'changes' => [
                'until' => $until->toIso8601String(),
                'phrase' => $phrase,
                'strikes' => $strikes,
            ],
            'source' => 'system',
        ]);

        return $until->toIso8601String();
    }
}
