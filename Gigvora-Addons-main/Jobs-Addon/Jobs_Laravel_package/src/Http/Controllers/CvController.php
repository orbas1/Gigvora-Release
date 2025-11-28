<?php

namespace Jobs\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Jobs\Models\CvTemplate;
use Jobs\Support\Analytics\JobsAnalytics;

class CvController extends Controller
{
    public function index(Request $request)
    {
        $candidateId = $request->user()?->id ?? $request->integer('candidate_id');
        $query = CvTemplate::query();

        if ($candidateId) {
            $query->where('candidate_id', $candidateId);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'candidate_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'array'],
            'is_default' => ['boolean'],
        ]);

        $cv = CvTemplate::create($data);

        JobsAnalytics::dispatch('cv_created', [
            'cv_template_id' => $cv->id,
            'candidate_id' => $cv->candidate_id,
        ]);

        JobsAnalytics::dispatch('job_seeker_profile_completed', [
            'candidate_id' => $cv->candidate_id,
        ]);

        return response()->json($cv, 201);
    }

    public function show(CvTemplate $cv)
    {
        return response()->json(['data' => $cv]);
    }

    public function update(Request $request, CvTemplate $cv)
    {
        $data = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'content' => ['sometimes', 'array'],
            'is_default' => ['boolean'],
        ]);

        $cv->update($data);

        JobsAnalytics::dispatch('cv_updated', [
            'cv_template_id' => $cv->id,
            'candidate_id' => $cv->candidate_id,
        ]);

        return response()->json(['data' => $cv]);
    }

    public function destroy(CvTemplate $cv)
    {
        $cv->delete();

        return response()->json(['message' => 'CV removed']);
    }
}
