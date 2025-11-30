<?php

namespace App\Observers;

use App\Services\UtilitiesInterviewSyncService;
use Jobs\Models\JobApplication;

class JobApplicationObserver
{
    public function __construct(protected UtilitiesInterviewSyncService $syncService)
    {
    }

    public function updated(JobApplication $application): void
    {
        if ($application->isDirty('status')) {
            $this->syncService->syncApplicationStatus($application, $application->getOriginal('status'));
        }

        if ($application->isDirty('notes')) {
            $this->syncService->syncApplicationStatus($application);
        }
    }

    public function deleted(JobApplication $application): void
    {
        $this->syncService->deleteApplicationStatus($application);
    }
}


