<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\Shared\Events;

use Gigvora\TalentAi\Domain\Headhunters\Models\HeadhunterPipelineItem;
use Illuminate\Foundation\Events\Dispatchable;

class HeadhunterStageMoved
{
    use Dispatchable;

    public function __construct(public HeadhunterPipelineItem $pipelineItem)
    {
    }
}
