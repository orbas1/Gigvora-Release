<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Policies;

use App\Models\User;
use Gigvora\TalentAi\Domain\Volunteering\Models\VolunteeringOpportunity;

class VolunteeringOpportunityPolicy
{
    public function view(User $user, VolunteeringOpportunity $opportunity): bool
    {
        return $user->can('manage_volunteering_opportunities') || $opportunity->creator_id === $user->id;
    }

    public function update(User $user, VolunteeringOpportunity $opportunity): bool
    {
        return $user->can('manage_volunteering_opportunities') || $opportunity->creator_id === $user->id;
    }
}
