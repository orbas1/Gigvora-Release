<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\Launchpad\Services;

use Gigvora\TalentAi\Domain\Launchpad\Models\LaunchpadApplication;
use Gigvora\TalentAi\Domain\Launchpad\Models\LaunchpadProgramme;

class LaunchpadProgressService
{
    public function calculateExperience(LaunchpadProgramme $programme): array
    {
        $hours = $programme->tasks()->sum('estimated_hours');
        $weeks = $programme->estimated_weeks;

        return ['hours' => $hours, 'weeks' => $weeks];
    }

    public function markCompleted(LaunchpadApplication $application, int $hours, int $weeks): LaunchpadApplication
    {
        $application->hours_gained = $hours;
        $application->weeks_gained = $weeks;
        $application->status = \Gigvora\TalentAi\Domain\Shared\Enums\LaunchpadApplicationStatus::Completed;
        $application->save();

        return $application;
    }
}
