<?php

namespace ProNetwork\Services;

use Illuminate\Support\Str;
use ProNetwork\Models\BadWord;
use ProNetwork\Models\BadWordRule;
use ProNetwork\Models\ModerationQueue;

class ModerationService
{
    public function queue(string $reason, $model, array $flags = []): ModerationQueue
    {
        return ModerationQueue::create([
            'moderatable_id' => $model->getKey(),
            'moderatable_type' => $model->getMorphClass(),
            'reason' => $reason,
            'flags' => $flags,
        ]);
    }

    public function applyBadWordRules(string $content): array
    {
        $cleanContent = $content;
        $matches = [];

        $badWords = BadWord::all();
        foreach ($badWords as $badWord) {
            if (Str::contains(Str::lower($content), Str::lower($badWord->phrase))) {
                $matches[] = $badWord->phrase;
                if ($badWord->replacement) {
                    $cleanContent = str_ireplace($badWord->phrase, $badWord->replacement, $cleanContent);
                }
            }
        }

        $rules = BadWordRule::where('active', true)->get();
        foreach ($rules as $rule) {
            if ($rule->action === 'block' && $matches) {
                return ['blocked' => true, 'content' => $cleanContent, 'matches' => $matches];
            }
        }

        return ['blocked' => false, 'content' => $cleanContent, 'matches' => $matches];
    }
}
