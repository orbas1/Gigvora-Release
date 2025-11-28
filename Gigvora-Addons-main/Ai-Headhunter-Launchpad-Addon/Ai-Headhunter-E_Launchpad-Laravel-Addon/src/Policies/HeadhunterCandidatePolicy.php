<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Policies;

use App\Models\User;
use Gigvora\TalentAi\Domain\Headhunters\Models\HeadhunterCandidate;

class HeadhunterCandidatePolicy
{
    public function view(User $user, HeadhunterCandidate $candidate): bool
    {
        return $user->can('manage_headhunter_pipelines') || $candidate->profile?->user_id === $user->id;
    }

    public function update(User $user, HeadhunterCandidate $candidate): bool
    {
        return $user->can('manage_headhunter_pipelines') || $candidate->profile?->user_id === $user->id;
    }
}
