<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\Shared\Enums;

enum HeadhunterMandateStatus: string
{
    case Open = 'open';
    case Paused = 'paused';
    case Closed = 'closed';
}
