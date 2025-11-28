<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\Shared\Enums;

enum HeadhunterPipelineStage: string
{
    case Sourced = 'sourced';
    case Screened = 'screened';
    case Shortlisted = 'shortlisted';
    case Interview = 'interview';
    case Offer = 'offer';
    case Placed = 'placed';
}
