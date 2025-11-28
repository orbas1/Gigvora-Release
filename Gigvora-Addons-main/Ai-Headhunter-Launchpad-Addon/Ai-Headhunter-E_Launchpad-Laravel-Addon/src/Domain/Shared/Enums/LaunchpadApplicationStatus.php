<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\Shared\Enums;

enum LaunchpadApplicationStatus: string
{
    case Submitted = 'submitted';
    case Shortlisted = 'shortlisted';
    case Interviewing = 'interviewing';
    case Offered = 'offered';
    case Rejected = 'rejected';
    case Completed = 'completed';
}
