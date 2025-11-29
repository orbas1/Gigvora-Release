<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\Headhunters\Services;

use Gigvora\TalentAi\Domain\Headhunters\Models\HeadhunterInterview;
use Gigvora\TalentAi\Domain\Headhunters\Models\HeadhunterPipelineItem;
use Illuminate\Support\Carbon;

class HeadhunterInterviewService
{
    public function scheduleInterview(HeadhunterPipelineItem $pipelineItem, int $scheduledBy, string $scheduledAt, ?string $summary = null): HeadhunterInterview
    {
        return $pipelineItem->interviews()->create([
            'scheduled_by' => $scheduledBy,
            'scheduled_at' => Carbon::parse($scheduledAt),
            'status' => 'scheduled',
            'summary' => $summary,
        ]);
    }

    public function updateInterview(HeadhunterInterview $interview, array $payload): HeadhunterInterview
    {
        $interview->fill($payload);
        $interview->save();

        return $interview;
    }
}
