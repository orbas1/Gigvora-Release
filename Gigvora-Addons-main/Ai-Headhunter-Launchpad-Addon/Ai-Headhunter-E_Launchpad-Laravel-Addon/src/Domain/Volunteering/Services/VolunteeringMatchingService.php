<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\Volunteering\Services;

use Gigvora\TalentAi\Domain\Volunteering\Models\VolunteeringOpportunity;
use Illuminate\Support\Collection;

class VolunteeringMatchingService
{
    public function match(array $criteria): Collection
    {
        return VolunteeringOpportunity::query()
            ->where('status', 'published')
            ->when($criteria['sector'] ?? null, fn ($q, $sector) => $q->where('sector', $sector))
            ->when($criteria['location'] ?? null, fn ($q, $location) => $q->where('location', 'like', "%{$location}%"))
            ->when($criteria['commitment'] ?? null, fn ($q, $commitment) => $q->where('commitment', $commitment))
            ->get();
    }
}
