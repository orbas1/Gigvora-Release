<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\Shared\Events;

use Gigvora\TalentAi\Domain\Volunteering\Models\VolunteeringApplication;
use Illuminate\Foundation\Events\Dispatchable;

class VolunteeringApplicationUpdated
{
    use Dispatchable;

    public function __construct(public VolunteeringApplication $application)
    {
    }
}
