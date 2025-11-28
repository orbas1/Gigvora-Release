<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Policies;

use App\Models\User;
use Gigvora\TalentAi\Domain\Headhunters\Models\HeadhunterMandate;

class HeadhunterMandatePolicy
{
    public function view(User $user, HeadhunterMandate $mandate): bool
    {
        return $user->can('manage_headhunter_pipelines') || $mandate->profile?->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->can('manage_headhunter_pipelines');
    }

    public function update(User $user, HeadhunterMandate $mandate): bool
    {
        return $user->can('manage_headhunter_pipelines') || $mandate->profile?->user_id === $user->id;
    }
}
