<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use ProNetwork\Http\Requests\UpdateProfessionalProfileRequest as LegacyUpdateProfessionalProfileRequest;
use ProNetwork\Models\ProfessionalProfile;
use ProNetwork\Services\ProfileEnhancementService;
use ProNetworkUtilitiesSecurityAnalytics\Http\Requests\UpdateProfessionalProfileRequest;

class ProfessionalProfileController extends Controller
{
    public function __construct(protected ProfileEnhancementService $profileEnhancementService)
    {
    }

    public function show(Request $request, ?int $userId = null)
    {
        $targetUserId = $userId ?? $request->user()->id;
        $profile = ProfessionalProfile::with([
            'skills',
            'certifications',
            'workHistories',
            'educationHistories',
            'references',
            'backgroundChecks',
            'interests',
            'opportunities',
        ])->where('user_id', $targetUserId)->firstOrFail();

        if ($request->expectsJson()) {
            return response()->json(['profile' => $profile]);
        }

        return view('pro_network::profile.show', [
            'profile' => $profile,
        ]);
    }

    public function edit(Request $request)
    {
        $profile = ProfessionalProfile::with(['skills', 'certifications', 'workHistories', 'educationHistories', 'references'])->where('user_id', $request->user()->id)->first();

        return view('pro_network::profile.edit', [
            'profile' => $profile,
        ]);
    }

    public function update(UpdateProfessionalProfileRequest|LegacyUpdateProfessionalProfileRequest $request)
    {
        $userId = $request->user()->id;
        $data = $request->validated();

        $profile = ProfessionalProfile::firstOrCreate(['user_id' => $userId]);
        Gate::authorize('update', $profile);

        $profile = $this->profileEnhancementService->updateProfile($userId, [
            'headline' => $data['headline'] ?? null,
            'tagline' => $data['tagline'] ?? null,
            'location' => $data['location'] ?? null,
            'top_skills' => $data['top_skills'] ?? [],
            'available_for_work' => $data['available_for_work'] ?? false,
            'interests' => $data['interests'] ?? [],
        ]);

        if (isset($data['skills'])) {
            $this->profileEnhancementService->syncSkills($userId, $data['skills'], $data['top_skills'] ?? []);
        }

        foreach ($data['experience'] ?? [] as $experience) {
            $this->profileEnhancementService->addWorkHistory($userId, $experience);
        }

        foreach ($data['education'] ?? [] as $education) {
            $this->profileEnhancementService->addEducation($userId, $education);
        }

        foreach ($data['certifications'] ?? [] as $certification) {
            $this->profileEnhancementService->addCertification($userId, $certification);
        }

        foreach ($data['references'] ?? [] as $reference) {
            $this->profileEnhancementService->addReference($userId, $reference);
        }

        if (array_key_exists('dbs_cleared', $data)) {
            $this->profileEnhancementService->logBackgroundCheck($userId, [
                'status' => $data['dbs_cleared'] ? 'cleared' : 'pending',
                'checked_at' => now(),
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json(['profile' => $profile->fresh()]);
        }

        return view('pro_network::profile.show', [
            'profile' => $profile->fresh(),
        ]);
    }
}
