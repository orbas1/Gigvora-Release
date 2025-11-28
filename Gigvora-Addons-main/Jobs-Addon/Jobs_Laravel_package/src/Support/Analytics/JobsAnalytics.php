<?php

namespace Jobs\Support\Analytics;

use Illuminate\Support\Facades\Log;
use Jobs\Events\AnalyticsEvent;

class JobsAnalytics
{
    public static function dispatch(string $event, array $properties = []): void
    {
        event(new AnalyticsEvent($event, $properties));

        Log::info('[jobs] analytics event dispatched', [
            'event' => $event,
            'properties' => $properties,
        ]);
    }
}
