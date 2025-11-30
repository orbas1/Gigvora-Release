<?php

namespace App\Listeners;

use App\Events\AnalyticsEvent as CoreAnalyticsEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Jobs\Events\AnalyticsEvent as JobsAnalyticsEvent;
use ProNetwork\Services\AnalyticsService;

class ForwardJobsAnalyticsEvent implements ShouldQueue
{
    use InteractsWithQueue;

    public string $queue = 'analytics';

    public function __construct(protected AnalyticsService $analytics)
    {
    }

    public function handle(JobsAnalyticsEvent|CoreAnalyticsEvent $event): void
    {
        if ($event instanceof JobsAnalyticsEvent && ! config('jobs.features.enabled')) {
            return;
        }

        $userId = $event->properties['user_id'] ?? optional(auth()->user())->id;

        $this->analytics->track(
            event: $event->name,
            properties: $event->properties + ['source' => 'jobs'],
            user: $userId,
            ip: request()?->ip()
        );
    }
}

