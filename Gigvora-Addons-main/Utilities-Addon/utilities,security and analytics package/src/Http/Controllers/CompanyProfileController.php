<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ProNetwork\Models\CompanyProfile;
use ProNetwork\Services\ProfileEnhancementService;
use ProNetworkUtilitiesSecurityAnalytics\Http\Requests\UpdateCompanyProfileRequest;

class CompanyProfileController extends Controller
{
    public function __construct(protected ProfileEnhancementService $profileEnhancementService)
    {
    }

    public function show(Request $request, CompanyProfile $company)
    {
        $company->loadMissing('employees');
        $employeeCount = $this->profileEnhancementService->computeEmployeeCount($company);

        if ($request->expectsJson()) {
            return response()->json([
                'profile' => $company,
                'employee_count' => $employeeCount,
            ]);
        }

        return view('pro_network::company.show', [
            'profile' => $company,
            'employeeCount' => $employeeCount,
        ]);
    }

    public function update(UpdateCompanyProfileRequest $request, CompanyProfile $company)
    {
        $payload = $request->validated();
        $profile = $this->profileEnhancementService->updateCompanyProfile($company->page_id, [
            'name' => $payload['name'] ?? null,
            'description' => $payload['description'] ?? null,
            'website' => $payload['website'] ?? null,
            'location' => $payload['location'] ?? null,
            'industries' => $payload['industries'] ?? [],
            'tags' => $payload['tags'] ?? [],
            'is_hiring' => $payload['is_hiring'] ?? false,
            'is_remote_friendly' => $payload['is_remote_friendly'] ?? false,
        ]);

        $employeeCount = $this->profileEnhancementService->computeEmployeeCount($profile);

        if ($request->expectsJson()) {
            return response()->json([
                'profile' => $profile->fresh(),
                'employee_count' => $employeeCount,
            ]);
        }

        return view('pro_network::company.show', [
            'profile' => $profile->fresh(),
            'employeeCount' => $employeeCount,
        ]);
    }
}
