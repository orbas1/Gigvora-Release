<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\Volunteering\Services;

use Gigvora\TalentAi\Domain\Volunteering\Models\VolunteeringApplication;
use Gigvora\TalentAi\Domain\Volunteering\Models\VolunteeringOpportunity;
use Gigvora\TalentAi\Domain\Shared\Enums\VolunteeringApplicationStatus;
use Gigvora\TalentAi\Domain\Shared\Enums\VolunteeringOpportunityStatus;
use Gigvora\TalentAi\Domain\Shared\Events\VolunteeringApplicationUpdated;

class VolunteeringService
{
    public function createOpportunity(array $payload): VolunteeringOpportunity
    {
        return VolunteeringOpportunity::create($payload);
    }

    public function updateOpportunity(VolunteeringOpportunity $opportunity, array $payload): VolunteeringOpportunity
    {
        $opportunity->fill($payload);
        $opportunity->save();

        return $opportunity;
    }

    public function publishOpportunity(VolunteeringOpportunity $opportunity): VolunteeringOpportunity
    {
        $opportunity->status = VolunteeringOpportunityStatus::Published;
        $opportunity->save();

        return $opportunity;
    }

    public function closeOpportunity(VolunteeringOpportunity $opportunity): VolunteeringOpportunity
    {
        $opportunity->status = VolunteeringOpportunityStatus::Closed;
        $opportunity->save();

        return $opportunity;
    }

    public function apply(VolunteeringOpportunity $opportunity, int $userId, array $payload): VolunteeringApplication
    {
        $application = $opportunity->applications()->create([
            'user_id' => $userId,
            'motivation' => $payload['motivation'] ?? null,
            'status' => VolunteeringApplicationStatus::Submitted,
        ]);

        VolunteeringApplicationUpdated::dispatch($application);

        return $application;
    }

    public function updateApplicationStatus(VolunteeringApplication $application, VolunteeringApplicationStatus $status): VolunteeringApplication
    {
        $application->status = $status;
        $application->save();
        VolunteeringApplicationUpdated::dispatch($application);

        return $application;
    }
}
