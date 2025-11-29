<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\Shared\Enums;

enum LaunchpadProgrammeStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Closed = 'closed';
}
