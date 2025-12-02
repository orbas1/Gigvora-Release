<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AnalyticsEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(public string $name, public array $properties = [])
    {
    }
}
