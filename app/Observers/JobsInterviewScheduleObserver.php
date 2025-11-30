<?php

namespace App\Observers;

use App\Services\UtilitiesInterviewSyncService;
use Jobs\Models\InterviewSchedule;

class JobsInterviewScheduleObserver
{
    public function __construct(protected UtilitiesInterviewSyncService $syncService)
    {
    }

    public function created(InterviewSchedule $schedule): void
    {
        $this->syncService->syncJobsInterview($schedule);
    }

    public function updated(InterviewSchedule $schedule): void
    {
        $this->syncService->syncJobsInterview($schedule);
    }

    public function deleted(InterviewSchedule $schedule): void
    {
        $this->syncService->deleteJobsInterview($schedule);
    }
}

