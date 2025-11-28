<?php

namespace Jobs\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Jobs\Events\JobPublished;
use Jobs\Http\Requests\JobRequest;
use Jobs\Models\Job;
use Jobs\Models\JobBookmark;
use Jobs\Models\CvTemplate;
use Jobs\Support\Analytics\JobsAnalytics;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::with('company')
            ->withCount('applications')
            ->published();

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . (string) $request->string('location') . '%');
        }

        if ($request->filled('keyword') || $request->filled('keywords')) {
            $term = (string) ($request->string('keyword') ?: $request->string('keywords'));
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', '%' . $term . '%')
                    ->orWhere('description', 'like', '%' . $term . '%');
            });
        }

        if ($request->filled('search')) {
            $term = (string) $request->string('search');
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', '%' . $term . '%')
                    ->orWhere('description', 'like', '%' . $term . '%');
            });
        }

        if ($request->filled('employment_type')) {
            $query->where('employment_type', $request->string('employment_type'));
        }

        if ($request->filled('workplace_type')) {
            $query->where('workplace_type', $request->string('workplace_type'));
        }

        if ($request->filled('posted')) {
            $days = match ($request->string('posted')) {
                '24h' => 1,
                'week' => 7,
                'month' => 30,
                default => null,
            };

            if ($days) {
                $query->where('published_at', '>=', now()->subDays($days));
            }
        }

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        if ($request->filled('sort') && $request->string('sort') === 'recent') {
            $query->latest('published_at');
        }

        $jobs = $query->paginate($request->integer('per_page', 15));

        if ($request->wantsJson()) {
            return response()->json(['data' => $jobs]);
        }

        if ($request->ajax()) {
            return View::make('vendor.jobs.components.job_results', ['jobs' => $jobs])->render();
        }

        return view('vendor.jobs.index', ['jobs' => $jobs]);
    }

    public function show(Job $job)
    {
        $job->load(['company', 'applications.candidate', 'screeningQuestions']);

        $similarJobs = Job::with('company')
            ->published()
            ->where('id', '!=', $job->id)
            ->where(function ($query) use ($job) {
                $query->where('company_id', $job->company_id)
                    ->orWhere('location', $job->location);
            })
            ->latest('published_at')
            ->take(4)
            ->get();

        if (request()->wantsJson()) {
            return response()->json(['data' => $job]);
        }

        return view('vendor.jobs.show', compact('job', 'similarJobs'));
    }

    public function store(JobRequest $request)
    {
        $job = Job::create($request->validated());

        $this->dispatchPublishedEvent($job);

        JobsAnalytics::dispatch('job_posted', ['job_id' => $job->id, 'company_id' => $job->company_id]);

        return response()->json(['data' => $job], 201);
    }

    public function update(JobRequest $request, Job $job)
    {
        $job->update($request->validated());

        $this->dispatchPublishedEvent($job);

        JobsAnalytics::dispatch('job_updated', ['job_id' => $job->id]);

        return response()->json(['data' => $job]);
    }

    public function destroy(Job $job)
    {
        $job->delete();

        JobsAnalytics::dispatch('job_closed', ['job_id' => $job->id]);

        return response()->json(['message' => 'Job removed']);
    }

    public function apply(Job $job)
    {
        $candidateId = auth()->id();
        $cvs = $candidateId ? CvTemplate::where('candidate_id', $candidateId)->get() : collect();

        return view('vendor.jobs.apply', [
            'job' => $job->load('company', 'screeningQuestions'),
            'cvs' => $cvs,
            'screeningQuestions' => $job->screeningQuestions,
        ]);
    }

    public function saved(Request $request)
    {
        $this->ensureAuthenticated();

        $savedJobs = JobBookmark::with('job.company')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        if ($request->wantsJson()) {
            return response()->json(['data' => $savedJobs]);
        }

        return view('vendor.jobs.saved', ['savedJobs' => $savedJobs]);
    }

    public function toggleSave(Request $request, Job $job)
    {
        $this->ensureAuthenticated();

        $data = $request->validate([
            'saved' => ['required', 'boolean'],
        ]);

        if ($data['saved']) {
            JobBookmark::firstOrCreate(['job_id' => $job->id, 'user_id' => $request->user()->id]);
        } else {
            JobBookmark::where('job_id', $job->id)->where('user_id', $request->user()->id)->delete();
        }

        return response()->json(['status' => 'ok']);
    }

    public function similar(Job $job)
    {
        $similar = Job::with('company')
            ->published()
            ->where('id', '!=', $job->id)
            ->where(function ($query) use ($job) {
                $query->where('company_id', $job->company_id)
                    ->orWhere('location', $job->location);
            })
            ->latest('published_at')
            ->take(10)
            ->get();

        return response()->json(['data' => $similar]);
    }

    protected function dispatchPublishedEvent(Job $job): void
    {
        if ($job->status === 'published') {
            JobPublished::dispatch($job);
        }
    }

    protected function ensureAuthenticated(): void
    {
        abort_unless(auth()->check(), 401, 'Authentication required');
    }
}
