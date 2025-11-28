<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\Shared\Events;

use Gigvora\TalentAi\Domain\AiWorkspace\Models\AiSession;
use Illuminate\Foundation\Events\Dispatchable;

class AiSessionCompleted
{
    use Dispatchable;

    public function __construct(public AiSession $session)
    {
    }
}
