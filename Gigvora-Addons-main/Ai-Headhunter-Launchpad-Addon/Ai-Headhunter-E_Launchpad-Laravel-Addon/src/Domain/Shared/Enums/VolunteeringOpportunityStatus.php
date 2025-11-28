<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\Shared\Enums;

enum VolunteeringOpportunityStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Closed = 'closed';
}
