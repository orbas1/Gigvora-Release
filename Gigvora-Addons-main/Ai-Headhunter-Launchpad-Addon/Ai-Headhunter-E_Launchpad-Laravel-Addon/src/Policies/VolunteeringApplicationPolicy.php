<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Policies;

use App\Models\User;
use Gigvora\TalentAi\Domain\Volunteering\Models\VolunteeringApplication;

class VolunteeringApplicationPolicy
{
    public function view(User $user, VolunteeringApplication $application): bool
    {
        return $user->can('manage_volunteering_opportunities') || $application->user_id === $user->id;
    }

    public function update(User $user, VolunteeringApplication $application): bool
    {
        return $user->can('manage_volunteering_opportunities') || $application->opportunity?->creator_id === $user->id;
    }
}
