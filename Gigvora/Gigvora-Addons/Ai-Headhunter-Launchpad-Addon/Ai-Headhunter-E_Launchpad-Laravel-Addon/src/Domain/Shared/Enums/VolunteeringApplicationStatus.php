<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\Shared\Enums;

enum VolunteeringApplicationStatus: string
{
    case Submitted = 'submitted';
    case Reviewing = 'reviewing';
    case Accepted = 'accepted';
    case Rejected = 'rejected';
    case Completed = 'completed';
}
