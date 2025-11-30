<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Gigvora\TalentAi\Domain\AiWorkspace\Models\AiSession;
use Gigvora\TalentAi\Domain\AiWorkspace\Models\AiUsageAggregate;
use Gigvora\TalentAi\Domain\Headhunters\Models\HeadhunterProfile;
use Gigvora\TalentAi\Domain\Headhunters\Models\HeadhunterPipelineItem;
use Gigvora\TalentAi\Domain\Launchpad\Models\LaunchpadApplication;
use Gigvora\TalentAi\Domain\Launchpad\Models\LaunchpadProgramme;
use Gigvora\TalentAi\Domain\Shared\Enums\HeadhunterMandateStatus;
use Gigvora\TalentAi\Domain\Shared\Enums\LaunchpadProgrammeStatus;
use Gigvora\TalentAi\Domain\Shared\Enums\VolunteeringApplicationStatus;
use Gigvora\TalentAi\Domain\Shared\Enums\VolunteeringOpportunityStatus;
use Gigvora\TalentAi\Domain\Volunteering\Models\VolunteeringApplication;
use Gigvora\TalentAi\Domain\Volunteering\Models\VolunteeringOpportunity;
use Illuminate\Support\Facades\Cache;

class TalentAiInsightsService
{
    protected const CACHE_SECONDS = 900;

    public function isEnabled(): bool
    {
        return (bool) config('gigvora_talent_ai.enabled', false)
            && class_exists(HeadhunterProfile::class);
    }

    public function profileData(User $user): array
    {
        if (! $this->isEnabled()) {
            return [];
        }

        return Cache::remember(
            $this->cacheKey('profile', $user->id),
            self::CACHE_SECONDS,
            function () use ($user) {
                $profile = $this->loadHeadhunterProfile($user);
                $launchpad = $this->loadLaunchpadApplication($user);
                $volunteering = $this->loadVolunteeringApplication($user);

                return [
                    'talent_ai_metrics' => [
                        'headhunter' => $this->buildHeadhunterMetrics($profile),
                        'launchpad' => $this->buildLaunchpadMetrics($launchpad),
                        'volunteering' => $this->buildVolunteeringMetrics($user),
                        'ai' => $this->buildAiMetrics($user),
                    ],
                    'talent_ai_cards' => $this->buildHighlightCards($profile, $launchpad, $volunteering),
                    'talent_ai_sidebar' => $this->buildSidebarSnapshot($user, $launchpad, $volunteering),
                ];
            }
        );
    }

    public function feedSummary(User $user): array
    {
        if (! $this->isEnabled()) {
            return [];
        }

        $profileData = $this->profileData($user);
        $metrics = $profileData['talent_ai_metrics'] ?? [];

        $headlineStats = array_filter([
            $this->makeHeadlineStat(
                get_phrase('Open mandates'),
                data_get($metrics, 'headhunter.open_mandates', 0),
                route('addons.talent_ai.headhunters.dashboard'),
                get_phrase('Track headhunter pipelines')
            ),
            $this->makeHeadlineStat(
                get_phrase('Launchpad progress'),
                data_get($metrics, 'launchpad.progress.percent'),
                route('addons.talent_ai.launchpad.programmes.index'),
                get_phrase('Modules completed')
            ),
            $this->makeHeadlineStat(
                get_phrase('Volunteering hours'),
                data_get($metrics, 'volunteering.hours', 0),
                route('addons.talent_ai.volunteering.opportunities.index'),
                get_phrase('Verified contributions'),
                suffix: 'h'
            ),
            $this->makeHeadlineStat(
                get_phrase('AI sessions this week'),
                data_get($metrics, 'ai.sessions_this_week', 0),
                route('addons.talent_ai.ai_workspace.index'),
                get_phrase('Workspace activity')
            ),
        ]);

        return [
            'stats' => $headlineStats,
            'cards' => [
                'launchpad' => $this->buildLaunchpadSummary($metrics),
                'volunteering' => $this->buildVolunteeringSummary($metrics),
                'ai' => $metrics['ai'] ?? null,
            ],
        ];
    }

    public function recommendationLanes(User $user): array
    {
        if (! $this->isEnabled()) {
            return [];
        }

        $programmes = $this->curatedProgrammes();
        $volunteering = $this->curatedVolunteering();
        $mandateStats = $this->buildHeadhunterMetrics($this->loadHeadhunterProfile($user));

        return array_values(array_filter([
            ! empty($programmes) ? [
                'icon' => 'fa-solid fa-graduation-cap',
                'label' => get_phrase('Launchpad programmes'),
                'items' => $programmes,
            ] : null,
            ! empty($volunteering) ? [
                'icon' => 'fa-solid fa-hand-holding-heart',
                'label' => get_phrase('Volunteering missions'),
                'items' => $volunteering,
            ] : null,
            ($mandateStats['pipeline_active'] ?? 0) > 0 ? [
                'icon' => 'fa-solid fa-user-tie',
                'label' => get_phrase('Headhunter pipeline'),
                'items' => [[
                    'title' => get_phrase('Active candidates'),
                    'subtitle' => collect($mandateStats['stage_breakdown'] ?? [])->map(function ($stage) {
                        return sprintf('%s: %s', ucfirst($stage['stage']), $stage['count']);
                    })->implode(' · '),
                    'meta' => get_phrase('View pipeline'),
                    'link' => route('addons.talent_ai.headhunters.dashboard'),
                ]],
            ] : null,
        ]));
    }

    protected function cacheKey(string $prefix, int $userId): string
    {
        return sprintf('talent_ai:%s:%d', $prefix, $userId);
    }

    protected function loadHeadhunterProfile(User $user): ?HeadhunterProfile
    {
        return HeadhunterProfile::query()
            ->with([
                'mandates' => function ($query) {
                    $query->where('status', HeadhunterMandateStatus::Open);
                },
                'mandates.pipelineItems',
            ])
            ->firstWhere('user_id', $user->id);
    }

    protected function loadLaunchpadApplication(User $user): ?LaunchpadApplication
    {
        return LaunchpadApplication::query()
            ->with(['programme.tasks', 'taskProgress', 'interviews'])
            ->where('user_id', $user->id)
            ->latest('id')
            ->first();
    }

    protected function loadVolunteeringApplication(User $user): ?VolunteeringApplication
    {
        return VolunteeringApplication::query()
            ->with('opportunity')
            ->where('user_id', $user->id)
            ->whereIn('status', [
                VolunteeringApplicationStatus::Accepted,
                VolunteeringApplicationStatus::Completed,
            ])
            ->latest('id')
            ->first();
    }

    protected function buildHeadhunterMetrics(?HeadhunterProfile $profile): array
    {
        if (! $profile) {
            return [
                'open_mandates' => 0,
                'pipeline_active' => 0,
                'stage_breakdown' => [],
                'link' => route('addons.talent_ai.headhunters.dashboard'),
            ];
        }

        $mandates = $profile->mandates ?? collect();
        $pipeline = $mandates->flatMap(fn ($mandate) => $mandate->pipelineItems ?? collect());

        $stageBreakdown = $pipeline
            ->groupBy(fn (HeadhunterPipelineItem $item) => $item->stage?->value ?? 'sourced')
            ->map(fn ($items, $stage) => ['stage' => $stage, 'count' => $items->count()])
            ->values()
            ->all();

        return [
            'open_mandates' => $mandates->count(),
            'pipeline_active' => $pipeline->count(),
            'stage_breakdown' => $stageBreakdown,
            'link' => route('addons.talent_ai.headhunters.dashboard'),
        ];
    }

    protected function buildLaunchpadMetrics(?LaunchpadApplication $application): array
    {
        if (! $application) {
            return [
                'title' => null,
                'progress' => null,
                'status' => null,
                'link' => route('addons.talent_ai.launchpad.programmes.index'),
            ];
        }

        $totalTasks = $application->programme?->tasks?->count() ?? 0;
        $completedTasks = $application->taskProgress?->count() ?? 0;
        $percent = $totalTasks > 0 ? (int) round(($completedTasks / $totalTasks) * 100) : null;

        return [
            'title' => $application->programme?->title,
            'status' => $application->status?->value,
            'progress' => [
                'completed' => $completedTasks,
                'total' => $totalTasks,
                'percent' => $percent,
            ],
            'link' => route('addons.talent_ai.launchpad.programmes.index'),
        ];
    }

    protected function buildVolunteeringMetrics(User $user): array
    {
        $hours = VolunteeringApplication::query()
            ->where('user_id', $user->id)
            ->whereIn('status', [
                VolunteeringApplicationStatus::Accepted,
                VolunteeringApplicationStatus::Completed,
            ])
            ->sum('hours_contributed');

        $applications = VolunteeringApplication::query()
            ->where('user_id', $user->id)
            ->whereIn('status', [
                VolunteeringApplicationStatus::Submitted,
                VolunteeringApplicationStatus::Reviewing,
                VolunteeringApplicationStatus::Accepted,
            ])
            ->count();

        return [
            'hours' => (int) $hours,
            'applications' => $applications,
            'link' => route('addons.talent_ai.volunteering.opportunities.index'),
        ];
    }

    protected function buildAiMetrics(User $user): array
    {
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = Carbon::now()->endOfWeek();

        $sessionsThisWeek = AiUsageAggregate::query()
            ->where('user_id', $user->id)
            ->whereBetween('period_start', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->sum('sessions_count');

        $lastSession = AiSession::query()
            ->where('user_id', $user->id)
            ->latest('id')
            ->first();

        return [
            'sessions_this_week' => (int) $sessionsThisWeek,
            'last_tool' => $lastSession?->tool,
            'last_run' => $lastSession?->created_at ? Carbon::parse($lastSession->created_at)->diffForHumans() : null,
            'link' => route('addons.talent_ai.ai_workspace.index'),
        ];
    }

    protected function buildHighlightCards(?HeadhunterProfile $profile, ?LaunchpadApplication $launchpad, ?VolunteeringApplication $volunteering): array
    {
        $cards = [];

        if ($launchpad && $launchpad->programme) {
            $progress = $this->buildLaunchpadMetrics($launchpad)['progress'];
            $cards[] = [
                'type' => 'launchpad',
                'title' => $launchpad->programme->title,
                'category' => $launchpad->programme->category,
                'progress' => $progress,
                'cta' => route('addons.talent_ai.launchpad.programmes.index'),
            ];
        }

        if ($volunteering && $volunteering->opportunity) {
            $cards[] = [
                'type' => 'volunteering',
                'title' => $volunteering->opportunity->title,
                'category' => $volunteering->opportunity->sector,
                'hours' => $volunteering->hours_contributed,
                'cta' => route('addons.talent_ai.volunteering.opportunities.index'),
            ];
        }

        if ($profile && ($profile->mandates?->count() ?? 0) > 0) {
            $cards[] = [
                'type' => 'headhunter',
                'title' => get_phrase('Mandates in progress'),
                'category' => implode(', ', array_slice($profile->industries ?? [], 0, 3)),
                'meta' => $profile->mandates?->count(),
                'cta' => route('addons.talent_ai.headhunters.dashboard'),
            ];
        }

        return $cards;
    }

    protected function buildSidebarSnapshot(User $user, ?LaunchpadApplication $launchpad, ?VolunteeringApplication $volunteering): array
    {
        $launchpadMetrics = $this->buildLaunchpadMetrics($launchpad);
        $volMetrics = $this->buildVolunteeringMetrics($user);
        $aiMetrics = $this->buildAiMetrics($user);

        return [
            'rows' => array_filter([
                [
                    'label' => get_phrase('Launchpad'),
                    'value' => $launchpadMetrics['progress']['percent'] ?? null,
                    'suffix' => '%',
                    'href' => $launchpadMetrics['link'],
                ],
                [
                    'label' => get_phrase('Volunteering'),
                    'value' => $volMetrics['hours'] ?? null,
                    'suffix' => 'h',
                    'href' => $volMetrics['link'],
                ],
                [
                    'label' => get_phrase('AI sessions'),
                    'value' => $aiMetrics['sessions_this_week'] ?? null,
                    'href' => $aiMetrics['link'],
                ],
            ]),
        ];
    }

    protected function makeHeadlineStat(string $label, ?int $value, ?string $route, string $description, ?string $suffix = null): ?array
    {
        if ($value === null) {
            return null;
        }

        return [
            'label' => $label,
            'value' => $value,
            'suffix' => $suffix,
            'description' => $description,
            'href' => $route,
        ];
    }

    protected function buildLaunchpadSummary(array $metrics): ?array
    {
        if (empty($metrics['launchpad']['title'])) {
            return null;
        }

        return [
            'title' => $metrics['launchpad']['title'],
            'category' => $metrics['launchpad']['status'],
            'progress' => $metrics['launchpad']['progress'],
            'link' => $metrics['launchpad']['link'],
        ];
    }

    protected function buildVolunteeringSummary(array $metrics): ?array
    {
        if (empty($metrics['volunteering'])) {
            return null;
        }

        return [
            'hours' => $metrics['volunteering']['hours'] ?? 0,
            'applications' => $metrics['volunteering']['applications'] ?? 0,
            'link' => $metrics['volunteering']['link'],
        ];
    }

    protected function curatedProgrammes(): array
    {
        return Cache::remember('talent_ai:launchpad_curated', self::CACHE_SECONDS, function () {
            return LaunchpadProgramme::query()
                ->where('status', LaunchpadProgrammeStatus::Published)
                ->latest('updated_at')
                ->limit(4)
                ->get()
                ->map(function (LaunchpadProgramme $programme) {
                    return [
                        'title' => $programme->title,
                        'subtitle' => $programme->category,
                        'meta' => sprintf(
                            '%s %s · %s %s',
                            $programme->estimated_hours ?? '—',
                            get_phrase('hrs'),
                            $programme->estimated_weeks ?? '—',
                            get_phrase('weeks')
                        ),
                        'link' => route('addons.talent_ai.launchpad.programmes.index').'#programme-'.$programme->id,
                        'secondary_link' => route('jobs').'?search='.urlencode($programme->title),
                    ];
                })
                ->all();
        });
    }

    protected function curatedVolunteering(): array
    {
        return Cache::remember('talent_ai:volunteering_curated', self::CACHE_SECONDS, function () {
            return VolunteeringOpportunity::query()
                ->where('status', VolunteeringOpportunityStatus::Published)
                ->latest('updated_at')
                ->limit(4)
                ->get()
                ->map(function (VolunteeringOpportunity $opportunity) {
                    return [
                        'title' => $opportunity->title,
                        'subtitle' => $opportunity->sector,
                        'meta' => $opportunity->location ?: get_phrase('Remote'),
                        'link' => route('addons.talent_ai.volunteering.opportunities.index').'#opportunity-'.$opportunity->id,
                    ];
                })
                ->all();
        });
    }
}

