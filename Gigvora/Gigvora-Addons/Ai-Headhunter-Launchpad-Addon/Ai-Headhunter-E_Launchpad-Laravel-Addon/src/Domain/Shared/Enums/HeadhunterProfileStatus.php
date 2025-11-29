<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\Shared\Enums;

enum HeadhunterProfileStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
}
