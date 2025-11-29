<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Support\Analytics;

use Illuminate\Support\Facades\Log;
use Jobi\WebinarNetworkingInterviewPodcast\Events\AnalyticsEvent;

class Analytics
{
    public static function track(string $event, array $payload = []): void
    {
        event(new AnalyticsEvent($event, $payload));

        if (config('app.debug')) {
            Log::info('[wnip] analytics event dispatched', ['event' => $event, 'payload' => $payload]);
        }
    }
}
