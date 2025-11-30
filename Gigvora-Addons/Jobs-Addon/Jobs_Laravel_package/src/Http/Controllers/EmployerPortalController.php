<?php

namespace Jobs\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Jobs\Http\Requests\JobRequest;
use Jobs\Models\CompanyProfile;
use Jobs\Models\InterviewSchedule;
use Jobs\Models\Job;
use Jobs\Models\JobApplication;
use Jobs\Support\Analytics\JobsAnalytics;

class EmployerPortalController extends Controller
{
    public function __construct()
    {
        $middleware = config('jobs.middleware.web_protected', ['web', 'auth']);
        $this->middleware($middleware);
    }

    public function dashboard(): View
    {
        $company = $this->companyFor(Auth::user());
        $metrics = $this->metricsFor($company);
        $jobs = $company->jobs()
            ->with(['company'])
            ->withCount('applications')
            ->latest('created_at')
            ->take(6)
            ->get();

        return view('vendor.jobs.employer.dashboard', compact('company', 'metrics', 'jobs'));
    }

    public function dashboardStats(Request $request): JsonResponse
    {
        $company = $this->companyFor(Auth::user());
        $range = (int) $request->integer('range', 7);
        $start = now()->subDays($range - 1)->startOfDay();

        $applications = JobApplication::selectRaw('DATE(applied_at) as day, COUNT(*) as total')
            ->where('applied_at', '>=', $start)
            ->whereHas('job', fn ($query) => $query->where('company_id', $company->id))
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $labels = [];
        $values = [];
        for ($i = 0; $i < $range; $i++) {
            $day = $start->copy()->addDays($i)->toDateString();
            $labels[] = $start->copy()->addDays($i)->format('M d');
            $values[] = (int) ($applications->firstWhere('day', $day)->total ?? 0);
        }

        return response()->json([
            'applications' => [
                'labels' => $labels,
                'values' => $values,
            ],
        ]);
    }

    public function jobs(Request $request): View
    {
        $company = $this->companyFor(Auth::user());
        $jobs = $company->jobs()
            ->with(['company'])
            ->withCount('applications')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest('created_at')
            ->paginate(config('jobs.defaults.pagination', 15));

        return view('vendor.jobs.employer.jobs.index', compact('jobs', 'company'));
    }

    public function jobWizard(?Job $job = null): View
    {
        if ($job) {
            $this->authorize('manage', $job);
        }

        return view('vendor.jobs.employer.jobs.wizard', [
            'job' => $job,
            'company' => $this->companyFor(Auth::user()),
        ]);
    }

    public function store(JobRequest $request): RedirectResponse
    {
        $company = $this->companyFor(Auth::user());
        $request->merge(['company_id' => $company->id]);
        $data = $request->validated();

        if (($data['status'] ?? null) === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $job = Job::create($data);
        JobsAnalytics::dispatch('job_posted', ['job_id' => $job->id, 'company_id' => $company->id]);

        return redirect()->route('employer.jobs.index')->with('status', 'job_created');
    }

    public function update(Job $job, JobRequest $request): RedirectResponse
    {
        $this->authorize('manage', $job);

        $request->merge(['company_id' => $job->company_id]);
        $data = $request->validated();

        if (($data['status'] ?? null) === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $job->update($data);
        JobsAnalytics::dispatch('job_updated', ['job_id' => $job->id]);

        return redirect()->route('employer.jobs.index')->with('status', 'job_updated');
    }

    public function ats(Job $job): View
    {
        $this->authorize('manage', $job);

        $pipelines = $job->applications()
            ->with(['candidate.user'])
            ->get()
            ->groupBy(fn (JobApplication $application) => $application->status ?? 'applied')
            ->map(function ($group) {
                return $group->map(function (JobApplication $application) {
                    $candidate = $application->candidate;
                    $user = $candidate?->user;

                    return (object) [
                        'id' => $application->id,
                        'name' => $user->name ?? get_phrase('Candidate').' #'.$application->candidate_id,
                        'headline' => $candidate?->headline,
                        'years_experience' => $candidate?->experience_years,
                        'tag' => Str::title($application->status ?? 'applied'),
                        'notes' => $application->notes,
                    ];
                })->all();
            })
            ->all();

        return view('vendor.jobs.employer.ats.board', compact('job', 'pipelines'));
    }

    public function interviews(): View
    {
        $company = $this->companyFor(Auth::user());
        $interviews = InterviewSchedule::with(['application.job', 'application.candidate.user'])
            ->whereHas('application.job', fn ($query) => $query->where('company_id', $company->id))
            ->latest('scheduled_at')
            ->paginate(config('jobs.defaults.pagination', 15));

        return view('vendor.jobs.employer.interviews.index', compact('interviews'));
    }

    public function interviewCalendar(): View
    {
        $company = $this->companyFor(Auth::user());
        $events = InterviewSchedule::with(['application.job', 'application.candidate.user'])
            ->whereHas('application.job', fn ($query) => $query->where('company_id', $company->id))
            ->orderBy('scheduled_at')
            ->get()
            ->map(function (InterviewSchedule $interview) {
                return [
                    'title' => $interview->application?->job?->title,
                    'date' => optional($interview->scheduled_at)->format('M d, Y h:i A'),
                    'candidate' => $interview->application?->candidate?->user?->name,
                    'status' => $interview->status,
                ];
            })
            ->all();

        return view('vendor.jobs.employer.interviews.calendar', ['events' => $events]);
    }

    public function scheduleInterview(Request $request): RedirectResponse
    {
        $company = $this->companyFor(Auth::user());
        $applicationId = $request->input('application_id');

        $application = JobApplication::where('id', $applicationId)
            ->whereHas('job', fn ($query) => $query->where('company_id', $company->id))
            ->firstOrFail();

        $interview = $application->interviews()->create([
            'scheduled_at' => $request->date('date', now()),
            'location' => $request->string('location')->toString(),
            'instructions' => $request->string('instructions')->toString(),
            'status' => 'scheduled',
        ]);

        JobsAnalytics::dispatch('interview_scheduled', ['interview_id' => $interview->id]);

        return back()->with('status', 'interview_scheduled');
    }

    public function updateInterview(Request $request, InterviewSchedule $interview): RedirectResponse
    {
        $this->authorizeInterview($interview);

        $interview->update([
            'scheduled_at' => $request->filled('date') ? $request->date('date') : $interview->scheduled_at,
            'status' => $request->input('status', $interview->status),
        ]);

        return back()->with('status', 'interview_updated');
    }

    public function destroyInterview(InterviewSchedule $interview): RedirectResponse
    {
        $this->authorizeInterview($interview);
        $interview->delete();

        return back()->with('status', 'interview_cancelled');
    }

    public function candidate(JobApplication $application): View
    {
        $this->authorize('manage', $application->job);

        $application->load(['candidate.user', 'screeningAnswers']);
        $candidate = (object) [
            'id' => $application->id,
            'name' => $application->candidate?->user?->name,
            'headline' => $application->candidate?->headline,
            'location' => $application->candidate?->location,
            'profile_url' => $application->candidate?->user?->id
                ? route('user.profile.view', $application->candidate->user->id)
                : null,
            'applied_at' => $application->applied_at,
            'stage' => $application->status,
            'cv_url' => $application->resume_path ? Storage::url($application->resume_path) : null,
            'screening_answers' => $application->screeningAnswers->map(function ($answer) {
                return [
                    'question' => $answer->question->question ?? null,
                    'answer' => $answer->answer,
                ];
            })->all(),
            'tags' => array_filter((array) $application->candidate?->skills),
        ];

        return view('vendor.jobs.employer.candidates.show', compact('candidate'));
    }

    public function company(): View
    {
        $company = $this->companyFor(Auth::user());

        return view('vendor.jobs.employer.company.edit', compact('company'));
    }

    public function updateCompany(Request $request): RedirectResponse
    {
        $company = $this->companyFor(Auth::user());
        $company->update($request->only([
            'name',
            'website',
            'location',
            'headline',
            'description',
        ]));

        JobsAnalytics::dispatch('employer_profile_completed', ['company_id' => $company->id]);

        return back()->with('status', 'company_updated');
    }

    public function billing(): View
    {
        $company = $this->companyFor(Auth::user());
        $plan = $company->subscriptions()->latest('renews_at')->first();
        $invoices = collect([]);

        return view('vendor.jobs.employer.billing.index', compact('plan', 'invoices'));
    }

    protected function companyFor($user): CompanyProfile
    {
        return CompanyProfile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $user->name."'s Company",
                'headline' => $user->profession,
                'location' => $user->address,
            ]
        );
    }

    protected function metricsFor(CompanyProfile $company): array
    {
        $jobIds = $company->jobs()->pluck('id');

        return [
            'active_jobs' => $company->jobs()->where('status', 'published')->count(),
            'new_applications' => JobApplication::whereIn('job_id', $jobIds)
                ->where('created_at', '>=', now()->subDays(7))
                ->count(),
            'interviews' => InterviewSchedule::whereHas('application.job', fn ($query) => $query->where('company_id', $company->id))
                ->where('scheduled_at', '>=', now()->subDays(30))
                ->count(),
            'offers' => JobApplication::whereIn('job_id', $jobIds)->where('status', 'offer')->count(),
        ];
    }

    protected function authorizeInterview(InterviewSchedule $interview): void
    {
        $this->authorize('manage', $interview->application->job);
    }
}

