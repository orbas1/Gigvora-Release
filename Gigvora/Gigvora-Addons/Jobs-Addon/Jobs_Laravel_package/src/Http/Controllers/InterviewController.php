<?php

namespace Jobs\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Jobs\Models\InterviewSchedule;
use Jobs\Models\JobApplication;
use Jobs\Support\Analytics\JobsAnalytics;

class InterviewController extends Controller
{
    public function index(JobApplication $jobApplication)
    {
        $interviews = $jobApplication->interviews()->get();

        return response()->json(['data' => $interviews]);
    }

    public function store(Request $request, JobApplication $jobApplication)
    {
        $data = $request->validate([
            'scheduled_at' => ['required', 'date'],
            'location' => ['nullable', 'string'],
            'instructions' => ['nullable', 'string'],
            'meeting_link' => ['nullable', 'string'],
            'status' => ['nullable', 'string'],
            'start' => ['nullable', 'date'],
            'end' => ['nullable', 'date'],
        ]);

        if (! isset($data['scheduled_at']) && isset($data['start'])) {
            $data['scheduled_at'] = $data['start'];
        }

        $interview = InterviewSchedule::create(array_merge($data, [
            'job_application_id' => $jobApplication->id,
        ]));

        JobsAnalytics::dispatch('interview_scheduled', [
            'application_id' => $jobApplication->id,
            'interview_id' => $interview->id,
        ]);

        return response()->json(['data' => $interview], 201);
    }
}
