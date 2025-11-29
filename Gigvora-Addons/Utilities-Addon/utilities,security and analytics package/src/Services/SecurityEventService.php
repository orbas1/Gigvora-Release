<?php

namespace ProNetwork\Services;

use Carbon\Carbon;
use ProNetwork\Models\SecurityEvent;
use ProNetwork\Support\Enums\SecurityEventType;

class SecurityEventService
{
    public function log(SecurityEventType $type, array $payload = []): SecurityEvent
    {
        return SecurityEvent::create([
            'user_id' => $payload['user_id'] ?? null,
            'type' => $type,
            'ip' => $payload['ip'] ?? request()->ip(),
            'user_agent' => $payload['user_agent'] ?? request()->userAgent(),
            'severity' => $payload['severity'] ?? 'info',
            'context' => $payload['context'] ?? [],
        ]);
    }

    public function tooManyAttempts(string $ip): bool
    {
        $max = (int) config('pro_network_utilities_security_analytics.security.brute_force.max_attempts', 5);
        $decay = (int) config('pro_network_utilities_security_analytics.security.brute_force.decay_minutes', 15);

        return SecurityEvent::where('ip', $ip)
            ->where('type', SecurityEventType::BRUTE_FORCE)
            ->where('created_at', '>=', Carbon::now()->subMinutes($decay))
            ->count() >= $max;
    }
}
