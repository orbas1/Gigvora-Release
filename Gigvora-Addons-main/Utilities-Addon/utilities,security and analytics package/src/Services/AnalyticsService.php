<?php

namespace ProNetwork\Services;

use Illuminate\Support\Facades\Log;
use ProNetwork\Models\AnalyticsEvent;
use ProNetwork\Models\AnalyticsMetric;
use ProNetwork\Support\DTOs\AnalyticsEventDto;
use Throwable;

class AnalyticsService
{
    public function track(string $event, array $properties = [], $user = null, ?string $ip = null): AnalyticsEvent
    {
        $payload = ['event' => $event, 'properties' => $properties, 'ip' => $ip];
        if ($user) {
            $payload['user_id'] = is_object($user) ? $user->id : $user;
        }

        $record = AnalyticsEvent::create($payload);

        if (config('pro_network_utilities_security_analytics.analytics.forward')) {
            Log::channel(config('pro_network_utilities_security_analytics.analytics.driver', 'stack'))
                ->info(config('pro_network_utilities_security_analytics.analytics.driver_alias'), $payload);
        }

        return $record;
    }

    public function trackMetric(string $entityType, int $entityId, string $metric, int $value = 1, array $meta = []): AnalyticsMetric
    {
        $record = AnalyticsMetric::firstOrCreate([
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'metric' => $metric,
        ]);

        $record->increment('value', $value);
        $record->update(['meta' => $meta + ($record->meta ?? [])]);

        return $record;
    }

    public function toDto(AnalyticsEvent $event): AnalyticsEventDto
    {
        return AnalyticsEventDto::fromModel($event);
    }
}
