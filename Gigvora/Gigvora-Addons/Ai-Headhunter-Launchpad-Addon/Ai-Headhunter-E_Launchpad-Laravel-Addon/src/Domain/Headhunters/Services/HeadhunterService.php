<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\Headhunters\Services;

use Gigvora\TalentAi\Domain\Headhunters\Models\HeadhunterCandidate;
use Gigvora\TalentAi\Domain\Headhunters\Models\HeadhunterMandate;
use Gigvora\TalentAi\Domain\Headhunters\Models\HeadhunterProfile;
use Gigvora\TalentAi\Domain\Shared\Enums\HeadhunterProfileStatus;
use Illuminate\Support\Collection;

class HeadhunterService
{
    public function applyForHeadhunter(int $userId, array $payload): HeadhunterProfile
    {
        $profile = HeadhunterProfile::firstOrCreate(
            ['user_id' => $userId],
            [
                'status' => HeadhunterProfileStatus::Pending,
                'bio' => $payload['bio'] ?? null,
                'industries' => $payload['industries'] ?? [],
                'skills' => $payload['skills'] ?? [],
            ]
        );

        if ($profile->exists) {
            $profile->fill([
                'bio' => $payload['bio'] ?? $profile->bio,
                'industries' => $payload['industries'] ?? $profile->industries,
                'skills' => $payload['skills'] ?? $profile->skills,
            ])->save();
        }

        return $profile;
    }

    public function approveProfile(HeadhunterProfile $profile): HeadhunterProfile
    {
        $profile->status = HeadhunterProfileStatus::Approved;
        $profile->approved_at = now();
        $profile->save();

        return $profile;
    }

    public function createMandate(HeadhunterProfile $profile, array $payload): HeadhunterMandate
    {
        return $profile->mandates()->create($payload);
    }

    public function updateMandate(HeadhunterMandate $mandate, array $payload): HeadhunterMandate
    {
        $mandate->fill($payload);
        $mandate->save();

        return $mandate;
    }

    public function createCandidate(HeadhunterProfile $profile, array $payload): HeadhunterCandidate
    {
        return $profile->candidates()->create($payload);
    }

    public function updateCandidate(HeadhunterCandidate $candidate, array $payload): HeadhunterCandidate
    {
        $candidate->fill($payload);
        $candidate->save();

        return $candidate;
    }

    public function searchCandidates(string $term, ?array $skills = null): Collection
    {
        return HeadhunterCandidate::query()
            ->when($skills, fn ($query) => $query->whereJsonContains('skills', $skills))
            ->when($term, fn ($query) => $query->where('name', 'like', "%{$term}%"))
            ->limit(50)
            ->get();
    }
}
