<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\AiWorkspace\Services;

use App\Models\User;
use Gigvora\TalentAi\Domain\AiWorkspace\Models\AiSession;
use Gigvora\TalentAi\Domain\AiWorkspace\Models\AiUsageAggregate;
use Gigvora\TalentAi\Domain\AiWorkspace\Models\AiUserSubscription;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class AiBillingService
{
    public function getSubscription(User $user): ?AiUserSubscription
    {
        return AiUserSubscription::with('plan')->where('user_id', $user->id)->where('status', 'active')->first();
    }

    public function checkLimits(User $user): bool
    {
        $subscription = $this->getSubscription($user);
        $tier = $subscription?->plan?->slug ?? 'basic';
        $limits = config("gigvora_talent_ai.ai.usage_limits.{$tier}");
        if (!$limits) {
            return true;
        }

        $aggregate = AiUsageAggregate::where('user_id', $user->id)
            ->where('period_start', '<=', Carbon::now()->toDateString())
            ->where('period_end', '>=', Carbon::now()->toDateString())
            ->first();

        if (!$aggregate) {
            return true;
        }

        return $aggregate->sessions_count < ($limits['daily'] ?? PHP_INT_MAX);
    }

    public function recordUsage(AiSession $session): void
    {
        $start = Carbon::now()->startOfDay();
        $end = Carbon::now()->endOfDay();
        $aggregate = AiUsageAggregate::firstOrCreate([
            'user_id' => $session->user_id,
            'period_start' => $start->toDateString(),
            'period_end' => $end->toDateString(),
        ]);

        $aggregate->tokens_used += ($session->prompt_tokens + $session->completion_tokens);
        $aggregate->sessions_count += 1;
        $aggregate->save();

        Log::channel(config('gigvora_talent_ai.analytics.log_channels.ai_costs', 'stack'))
            ->info('AI usage recorded', ['session_id' => $session->id]);
    }
}
