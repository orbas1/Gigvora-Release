<?php

namespace ProNetwork\Support\DTOs;

use ProNetwork\Models\AnalyticsEvent;

class AnalyticsEventDto
{
    public function __construct(
        public readonly string $event,
        public readonly array $properties = [],
        public readonly ?int $userId = null,
        public readonly ?string $ip = null,
    ) {
    }

    public static function fromModel(AnalyticsEvent $event): self
    {
        return new self(
            $event->event,
            $event->properties ?? [],
            $event->user_id,
            $event->ip
        );
    }
}
