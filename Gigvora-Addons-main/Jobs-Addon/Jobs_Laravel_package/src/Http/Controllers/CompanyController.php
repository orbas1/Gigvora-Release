<?php

namespace Jobs\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Jobs\Models\CompanyProfile;
use Jobs\Support\Analytics\JobsAnalytics;

class CompanyController extends Controller
{
    public function index()
    {
        return response()->json(CompanyProfile::paginate());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string'],
            'headline' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'website' => ['nullable', 'string'],
            'location' => ['nullable', 'string'],
            'logo_path' => ['nullable', 'string'],
            'cover_path' => ['nullable', 'string'],
        ]);

        $company = CompanyProfile::create($data);

        JobsAnalytics::dispatch('employer_profile_completed', [
            'company_id' => $company->id,
            'user_id' => $company->user_id,
        ]);

        return response()->json($company, 201);
    }
}
