<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\Shared\Events;

use Gigvora\TalentAi\Domain\Launchpad\Models\LaunchpadApplication;
use Illuminate\Foundation\Events\Dispatchable;

class LaunchpadApplicationSubmitted
{
    use Dispatchable;

    public function __construct(public LaunchpadApplication $application)
    {
    }
}
