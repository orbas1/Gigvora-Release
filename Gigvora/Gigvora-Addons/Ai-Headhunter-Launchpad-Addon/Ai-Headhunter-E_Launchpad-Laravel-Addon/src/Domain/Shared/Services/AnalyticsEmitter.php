<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\Shared\Services;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Log;

class AnalyticsEmitter
{
    public function __construct(private Dispatcher $events)
    {
    }

    public function emit(string $event, array $payload = []): void
    {
        if (!config('gigvora_talent_ai.analytics.emit_events')) {
            return;
        }

        $this->events->dispatch($event, $payload);
        Log::channel(config('gigvora_talent_ai.analytics.log_channels.ai_costs', 'stack'))
            ->debug('Talent AI analytics event', ['event' => $event, 'payload' => $payload]);
    }
}
