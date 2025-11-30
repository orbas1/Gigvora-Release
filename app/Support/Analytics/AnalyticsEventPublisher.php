<?php

namespace App\Support\Analytics;

use App\Events\AnalyticsEvent;
use App\Support\Authorization\PermissionMatrix;
use Illuminate\Contracts\Auth\Authenticatable;

class AnalyticsEventPublisher
{
    public function __construct(protected PermissionMatrix $matrix)
    {
    }

    public function publish(string $domain, string $key, array $properties = [], ?Authenticatable $actor = null): ?string
    {
        $eventName = $this->matrix->eventName($domain, $key);

        if (! $eventName) {
            return null;
        }

        $payload = $this->matrix->analyticsProperties($actor, $properties);

        event(new AnalyticsEvent($eventName, $payload));

        return $eventName;
    }
}
