<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Policies;

use App\Models\User;
use Gigvora\TalentAi\Domain\Headhunters\Models\HeadhunterPipelineItem;

class HeadhunterPipelineItemPolicy
{
    public function update(User $user, HeadhunterPipelineItem $item): bool
    {
        return $user->can('manage_headhunter_pipelines') || $item->mandate?->profile?->user_id === $user->id;
    }
}
