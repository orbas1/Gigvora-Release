<?php

namespace ProNetwork\Services;

use ProNetwork\Models\CompanyEmployee;
use ProNetwork\Models\CompanyProfile;
use ProNetwork\Models\ProfessionalProfile;
use ProNetwork\Models\ProfileBackgroundCheck;
use ProNetwork\Models\ProfileCertification;
use ProNetwork\Models\ProfileEducationHistory;
use ProNetwork\Models\ProfileInterest;
use ProNetwork\Models\ProfileOpportunity;
use ProNetwork\Models\ProfileReference;
use ProNetwork\Models\ProfileSkill;
use ProNetwork\Models\ProfileWorkHistory;

class ProfileEnhancementService
{
    public function updateProfile(int $userId, array $data): ProfessionalProfile
    {
        return ProfessionalProfile::updateOrCreate(['user_id' => $userId], $data);
    }

    public function syncSkills(int $userId, array $skills, array $topFive = []): void
    {
        ProfileSkill::where('user_id', $userId)->delete();
        foreach ($skills as $skill) {
            ProfileSkill::create([
                'user_id' => $userId,
                'name' => $skill,
                'is_top_five' => in_array($skill, $topFive, true),
            ]);
        }
    }

    public function addCertification(int $userId, array $payload): ProfileCertification
    {
        $payload['user_id'] = $userId;
        return ProfileCertification::create($payload);
    }

    public function addWorkHistory(int $userId, array $payload): ProfileWorkHistory
    {
        $payload['user_id'] = $userId;
        return ProfileWorkHistory::create($payload);
    }

    public function addEducation(int $userId, array $payload): ProfileEducationHistory
    {
        $payload['user_id'] = $userId;
        return ProfileEducationHistory::create($payload);
    }

    public function addReference(int $userId, array $payload): ProfileReference
    {
        $payload['user_id'] = $userId;
        return ProfileReference::create($payload);
    }

    public function logBackgroundCheck(int $userId, array $payload): ProfileBackgroundCheck
    {
        $payload['user_id'] = $userId;
        return ProfileBackgroundCheck::create($payload);
    }

    public function addInterest(int $userId, string $interest, int $weight = 0): ProfileInterest
    {
        return ProfileInterest::updateOrCreate([
            'user_id' => $userId,
            'interest' => $interest,
        ], [
            'weight' => $weight,
        ]);
    }

    public function addOpportunity(int $userId, array $payload): ProfileOpportunity
    {
        $payload['user_id'] = $userId;
        return ProfileOpportunity::create($payload);
    }

    public function updateCompanyProfile(int $pageId, array $payload): CompanyProfile
    {
        return CompanyProfile::updateOrCreate(['page_id' => $pageId], $payload);
    }

    public function addEmployee(int $companyProfileId, int $userId, array $payload = []): CompanyEmployee
    {
        return CompanyEmployee::updateOrCreate(
            ['company_profile_id' => $companyProfileId, 'user_id' => $userId],
            [
                'role_title' => $payload['role_title'] ?? null,
                'started_at' => $payload['started_at'] ?? null,
                'ended_at' => $payload['ended_at'] ?? null,
                'is_current' => $payload['is_current'] ?? true,
            ]
        );
    }

    public function computeEmployeeCount(CompanyProfile $profile): int
    {
        $count = $profile->employees()->where('is_current', true)->count();
        $profile->update(['employee_count' => $count]);

        return $count;
    }
}
