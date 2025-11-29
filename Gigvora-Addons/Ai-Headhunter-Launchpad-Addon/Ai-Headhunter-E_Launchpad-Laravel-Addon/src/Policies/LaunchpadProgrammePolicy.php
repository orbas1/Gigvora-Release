<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Policies;

use App\Models\User;
use Gigvora\TalentAi\Domain\Launchpad\Models\LaunchpadProgramme;

class LaunchpadProgrammePolicy
{
    public function view(User $user, LaunchpadProgramme $programme): bool
    {
        return $user->can('manage_launchpad_programmes') || $programme->creator_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->can('manage_launchpad_programmes');
    }

    public function update(User $user, LaunchpadProgramme $programme): bool
    {
        return $user->can('manage_launchpad_programmes') || $programme->creator_id === $user->id;
    }
}
