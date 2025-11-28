<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\Launchpad\Services;

use Gigvora\TalentAi\Domain\Launchpad\Models\LaunchpadProgramme;
use Gigvora\TalentAi\Domain\Shared\Enums\LaunchpadProgrammeStatus;
use Illuminate\Support\Arr;

class LaunchpadProgrammeService
{
    public function createProgramme(array $payload): LaunchpadProgramme
    {
        return LaunchpadProgramme::create($payload);
    }

    public function updateProgramme(LaunchpadProgramme $programme, array $payload): LaunchpadProgramme
    {
        $programme->fill($payload);
        $programme->save();

        return $programme;
    }

    public function publishProgramme(LaunchpadProgramme $programme): LaunchpadProgramme
    {
        $programme->status = LaunchpadProgrammeStatus::Published;
        $programme->save();

        return $programme;
    }

    public function closeProgramme(LaunchpadProgramme $programme): LaunchpadProgramme
    {
        $programme->status = LaunchpadProgrammeStatus::Closed;
        $programme->save();

        return $programme;
    }

    public function attachTasks(LaunchpadProgramme $programme, array $tasks): void
    {
        $programme->tasks()->delete();
        foreach ($tasks as $index => $task) {
            $programme->tasks()->create([
                'title' => $task['title'],
                'description' => Arr::get($task, 'description'),
                'order' => $index,
                'estimated_hours' => Arr::get($task, 'estimated_hours', 0),
            ]);
        }
    }
}
