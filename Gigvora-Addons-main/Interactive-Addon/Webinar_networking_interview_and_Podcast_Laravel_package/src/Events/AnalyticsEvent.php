<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AnalyticsEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public string $name, public array $payload = [])
    {
    }
}
