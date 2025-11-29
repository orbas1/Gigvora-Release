<?php

namespace Jobs\Http\Controllers;

use Illuminate\Routing\Controller;
use Jobs\Events\ApplicationSubmitted;
use Jobs\Http\Requests\ApplicationRequest;
use Jobs\Models\Job;
use Jobs\Models\JobApplication;
use Jobs\Models\ScreeningAnswer;
use Jobs\Support\Analytics\JobsAnalytics;

class ApplicationController extends Controller
{
    public function index()
    {
        $query = JobApplication::with(['job.company', 'candidate', 'cv', 'coverLetter', 'screeningAnswers']);

        if (request()->filled('company_id')) {
            $query->whereHas('job', function ($q) {
                $q->where('company_id', request()->integer('company_id'));
            });
        }

        if (request()->filled('candidate_id')) {
            $query->where('candidate_id', request()->integer('candidate_id'));
        }

        if (request()->filled('status')) {
            $query->where('status', request()->string('status'));
        }

        return response()->json(['data' => $query->paginate(request()->integer('per_page', 15))]);
    }

    public function store(ApplicationRequest $request)
    {
        $job = Job::findOrFail($request->integer('job_id'));

        $data = $request->validated();
        $data['candidate_id'] = $data['candidate_id'] ?? $request->user()?->id;
        $data['status'] = $data['status'] ?? 'applied';
        $data['applied_at'] = now();

        $application = $job->applications()->create($data);

        if ($request->filled('answers')) {
            foreach ((array) $request->input('answers') as $answer) {
                ScreeningAnswer::create([
                    'job_application_id' => $application->id,
                    'screening_question_id' => $answer['screening_question_id'] ?? null,
                    'answer' => $answer['answer'] ?? null,
                ]);
            }
            JobsAnalytics::dispatch('screening_questions_answered', ['application_id' => $application->id]);
        }

        ApplicationSubmitted::dispatch($application);

        JobsAnalytics::dispatch('job_applied', ['application_id' => $application->id, 'job_id' => $job->id]);

        return response()->json(['data' => $application->load(['job', 'candidate'])], 201);
    }

    public function update(ApplicationRequest $request, JobApplication $jobApplication)
    {
        $originalStatus = $jobApplication->status;
        $jobApplication->update($request->validated());

        if ($originalStatus !== $jobApplication->status) {
            JobsAnalytics::dispatch('application_status_changed', [
                'application_id' => $jobApplication->id,
                'from' => $originalStatus,
                'to' => $jobApplication->status,
            ]);
        }

        return response()->json(['data' => $jobApplication->load(['job', 'candidate'])]);
    }

    public function show(JobApplication $jobApplication)
    {
        return response()->json(['data' => $jobApplication->load(['job.company', 'candidate', 'screeningAnswers', 'interviews'])]);
    }

    public function storeForJob(Job $job, ApplicationRequest $request)
    {
        $payload = $request->validated();
        $payload['job_id'] = $job->id;

        $request->merge($payload);

        return $this->store($request);
    }

    public function withdraw(JobApplication $jobApplication)
    {
        $jobApplication->update(['status' => 'withdrawn']);

        JobsAnalytics::dispatch('application_status_changed', [
            'application_id' => $jobApplication->id,
            'to' => 'withdrawn',
        ]);

        return response()->json(['message' => 'Application withdrawn']);
    }
}
