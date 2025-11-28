<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\Launchpad\Services;

use Gigvora\TalentAi\Domain\Launchpad\Models\LaunchpadApplication;
use Gigvora\TalentAi\Domain\Launchpad\Models\LaunchpadProgramme;
use Gigvora\TalentAi\Domain\Shared\Enums\LaunchpadApplicationStatus;
use Gigvora\TalentAi\Domain\Shared\Events\LaunchpadApplicationSubmitted;

class LaunchpadApplicationService
{
    public function apply(LaunchpadProgramme $programme, int $userId, array $payload): LaunchpadApplication
    {
        $application = $programme->applications()->create([
            'user_id' => $userId,
            'motivation' => $payload['motivation'] ?? null,
            'status' => LaunchpadApplicationStatus::Submitted,
        ]);

        LaunchpadApplicationSubmitted::dispatch($application);

        return $application;
    }

    public function updateStatus(LaunchpadApplication $application, LaunchpadApplicationStatus $status): LaunchpadApplication
    {
        $application->status = $status;
        if ($status === LaunchpadApplicationStatus::Completed) {
            $application->reference_issued = $application->reference_issued ?? false;
            $application->qualification_issued = $application->qualification_issued ?? false;
        }
        $application->save();

        return $application;
    }

    public function issueOutcomes(LaunchpadApplication $application, bool $reference, bool $qualification): LaunchpadApplication
    {
        $application->reference_issued = $reference;
        $application->qualification_issued = $qualification;
        $application->save();

        return $application;
    }
}
