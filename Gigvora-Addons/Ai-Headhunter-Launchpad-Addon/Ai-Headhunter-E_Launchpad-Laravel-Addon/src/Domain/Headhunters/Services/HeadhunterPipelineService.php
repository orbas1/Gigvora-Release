<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\Headhunters\Services;

use Gigvora\TalentAi\Domain\Headhunters\Models\HeadhunterMandate;
use Gigvora\TalentAi\Domain\Headhunters\Models\HeadhunterPipelineItem;
use Gigvora\TalentAi\Domain\Shared\Enums\HeadhunterPipelineStage;
use Gigvora\TalentAi\Domain\Shared\Events\HeadhunterStageMoved;
use Illuminate\Support\Carbon;

class HeadhunterPipelineService
{
    public function attachToPipeline(HeadhunterMandate $mandate, int $candidateId, string $stage = HeadhunterPipelineStage::Sourced->value): HeadhunterPipelineItem
    {
        return HeadhunterPipelineItem::create([
            'headhunter_mandate_id' => $mandate->id,
            'headhunter_candidate_id' => $candidateId,
            'stage' => $stage,
            'moved_at' => Carbon::now(),
        ]);
    }

    public function moveStage(HeadhunterPipelineItem $item, HeadhunterPipelineStage $stage, ?string $notes = null): HeadhunterPipelineItem
    {
        $item->stage = $stage;
        $item->notes = $notes ?? $item->notes;
        $item->moved_at = Carbon::now();
        $item->save();

        HeadhunterStageMoved::dispatch($item);

        return $item;
    }
}
