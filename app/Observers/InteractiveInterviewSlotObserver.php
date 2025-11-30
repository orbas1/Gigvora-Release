<?php

namespace App\Observers;

use App\Services\UtilitiesInterviewSyncService;
use Jobi\WebinarNetworkingInterviewPodcast\Models\InterviewSlot;

class InteractiveInterviewSlotObserver
{
    public function __construct(protected UtilitiesInterviewSyncService $syncService)
    {
    }

    public function created(InterviewSlot $slot): void
    {
        $this->syncService->syncInteractiveSlot($slot);
    }

    public function updated(InterviewSlot $slot): void
    {
        $this->syncService->syncInteractiveSlot($slot);
    }

    public function deleted(InterviewSlot $slot): void
    {
        $this->syncService->deleteInteractiveSlot($slot);
    }
}

