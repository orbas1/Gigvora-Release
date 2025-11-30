<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Jobi\WebinarNetworkingInterviewPodcast\Models\Interview;
use Jobi\WebinarNetworkingInterviewPodcast\Models\NetworkingSession;
use Jobi\WebinarNetworkingInterviewPodcast\Models\PodcastEpisode;
use Jobi\WebinarNetworkingInterviewPodcast\Models\Recording;
use Jobi\WebinarNetworkingInterviewPodcast\Models\Webinar;

class LiveEventsExperienceService
{
    public function __construct(
        protected AdvertisementSurfaceService $advertisements,
    ) {
    }

    public function isEnabled(): bool
    {
        return class_exists(Webinar::class);
    }

    public function hubOverview(): array
    {
        $ad = $this->advertisements->forSlot('live-hub');

        if (! $this->isEnabled()) {
            return [
                'metrics' => [],
                'sections' => [],
                'ad' => $ad,
            ];
        }

        return [
            'metrics' => $this->metrics(),
            'sections' => $this->sections(),
            'ad' => $ad,
        ];
    }

    public function recommendationCards(int $limit = 3): array
    {
        if (! $this->isEnabled()) {
            return [];
        }

        $pool = collect()
            ->merge(
                $this->upcomingWebinars($limit)->map(fn ($webinar) => $this->formatRecommendation(
                    title: $webinar->title,
                    subtitle: optional($webinar->host)->name ?? __('Live webinar'),
                    startsAt: $webinar->starts_at?->timestamp,
                    href: $this->route('wnip.webinars.show', $webinar)
                ))
            )
            ->merge(
                $this->upcomingNetworking($limit)->map(fn ($session) => $this->formatRecommendation(
                    title: $session->title,
                    subtitle: __('Rotations every :seconds s', ['seconds' => $session->rotation_interval ?? 60]),
                    startsAt: $session->starts_at?->timestamp,
                    href: $this->route('wnip.networking.show', $session)
                ))
            )
            ->merge(
                $this->latestPodcastEpisodes($limit)->map(fn ($episode) => $this->formatRecommendation(
                    title: $episode->title,
                    subtitle: optional($episode->series)->title ?? __('Podcast episode'),
                    startsAt: $episode->published_at?->timestamp,
                    href: $episode->audio_path ?: $this->route('wnip.podcasts.series', $episode->series)
                ))
            )
            ->merge(
                $this->upcomingInterviews($limit)->map(fn ($interview) => $this->formatRecommendation(
                    title: $interview->title,
                    subtitle: __('Interview • :duration mins', ['duration' => $interview->duration_minutes ?? 30]),
                    startsAt: $interview->scheduled_at?->timestamp,
                    href: $this->route('wnip.interviews.show', $interview) ?? $this->route('wnip.interviews.index')
                ))
            );

        return $pool
            ->sortBy('sort_key')
            ->take($limit)
            ->map(function (array $card) {
                unset($card['sort_key']);

                return $card;
            })
            ->values()
            ->all();
    }

    protected function metrics(): array
    {
        $now = now();
        $endOfWeek = $now->copy()->endOfWeek();

        $upcomingWebinars = $this->webinarsQuery()
            ->whereBetween('starts_at', [$now, $endOfWeek])
            ->count();

        $networkingThisWeek = NetworkingSession::query()
            ->whereBetween('starts_at', [$now, $endOfWeek])
            ->count();

        $interviewsThisWeek = Interview::query()
            ->whereBetween('scheduled_at', [$now, $endOfWeek])
            ->count();

        $recordedSeconds = Recording::query()
            ->where('created_at', '>=', now()->subDays(30))
            ->sum('duration');

        $recordedHours = (int) round($recordedSeconds / 3600);

        return [
            [
                'label' => __('Upcoming webinars'),
                'value' => $upcomingWebinars,
                'meta' => __('This week'),
            ],
            [
                'label' => __('Networking lounges'),
                'value' => $networkingThisWeek,
                'meta' => __('Next 7 days'),
            ],
            [
                'label' => __('Interviews scheduled'),
                'value' => $interviewsThisWeek,
                'meta' => __('Week to date'),
            ],
            [
                'label' => __('Hours recorded'),
                'value' => $recordedHours,
                'meta' => __('Past 30 days'),
            ],
        ];
    }

    protected function sections(): array
    {
        $webinars = $this->upcomingWebinars()
            ->map(function ($webinar) {
                $count = $webinar->registrations_count ?? 0;

                return [
                    'title' => $webinar->title,
                    'meta' => $webinar->starts_at?->format('M j • g:i A'),
                    'caption' => optional($webinar->host)->name,
                    'status' => $webinar->is_live ? __('Live now') : __('Scheduled'),
                    'tag' => $webinar->is_paid ? __('Paid') : __('Free'),
                    'detail' => trans_choice('{0}No registrants|{1}1 registrant|[2,*]:count registrants', $count, ['count' => $count]),
                    'href' => $this->route('wnip.webinars.show', $webinar),
                    'description' => Str::limit((string) $webinar->description, 120),
                ];
            });

        $networking = $this->upcomingNetworking()
            ->map(function ($session) {
                $count = $session->participants_count ?? 0;

                return [
                    'title' => $session->title,
                    'meta' => $session->starts_at?->format('M j • g:i A'),
                    'caption' => __('Rotations every :seconds s', ['seconds' => $session->rotation_interval ?? 60]),
                    'status' => $session->status ? ucfirst($session->status) : __('Scheduled'),
                    'tag' => $session->is_paid ? __('Paid') : __('Open'),
                    'detail' => trans_choice('{0}Be first to register|{1}1 attendee|[2,*]:count attendees', $count, ['count' => $count]),
                    'href' => $this->route('wnip.networking.show', $session),
                    'description' => Str::limit((string) $session->description, 120),
                ];
            });

        $episodes = $this->latestPodcastEpisodes()
            ->map(function ($episode) {
                return [
                    'title' => $episode->title,
                    'meta' => $episode->published_at?->format('M j • g:i A') ?? __('Draft'),
                    'caption' => optional($episode->series)->title,
                    'status' => __('Replay'),
                    'tag' => __('Audio'),
                    'detail' => __('Duration: :minutes min', ['minutes' => $episode->duration ?? '—']),
                    'href' => $episode->audio_path ?: $this->route('wnip.podcasts.series', $episode->series),
                    'description' => Str::limit((string) $episode->description, 120),
                ];
            });

        $interviews = $this->upcomingInterviews()
            ->map(function ($interview) {
                return [
                    'title' => $interview->title,
                    'meta' => $interview->scheduled_at?->format('M j • g:i A'),
                    'caption' => optional($interview->host)->name,
                    'status' => __('Interview'),
                    'tag' => $interview->is_panel ? __('Panel') : __('1:1'),
                    'detail' => __('Duration • :minutes min', ['minutes' => $interview->duration_minutes ?? 30]),
                    'href' => $this->route('wnip.interviews.show', $interview) ?? $this->route('wnip.interviews.index'),
                    'description' => Str::limit((string) $interview->description, 120),
                ];
            });

        return [
            [
                'key' => 'webinars',
                'eyebrow' => __('Webinars'),
                'title' => __('Upcoming webinars'),
                'description' => __('Host-led live sessions with ticketing, Q&A, and high-fidelity streaming.'),
                'items' => $webinars->toArray(),
                'actions' => array_filter([
                    [
                        'label' => __('Browse webinars'),
                        'route' => $this->route('wnip.webinars.index') ?? $this->route('liveCenter.hub'),
                        'type' => 'primary',
                    ],
                    [
                        'label' => __('Host a webinar'),
                        'route' => url('/events/webinars'),
                        'type' => 'ghost',
                    ],
                ]),
            ],
            [
                'key' => 'networking',
                'eyebrow' => __('Networking'),
                'title' => __('Networking lounges & meetups'),
                'description' => __('Timed rotations, curated cohorts, and contact sharing for members and hosts.'),
                'items' => $networking->toArray(),
                'actions' => array_filter([
                    [
                        'label' => __('View sessions'),
                        'route' => $this->route('wnip.networking.index'),
                        'type' => 'primary',
                    ],
                ]),
            ],
            [
                'key' => 'podcasts',
                'eyebrow' => __('Podcasts'),
                'title' => __('Fresh episodes & replays'),
                'description' => __('Listen to on-demand conversations, highlights, and live tapings.'),
                'items' => $episodes->toArray(),
                'actions' => array_filter([
                    [
                        'label' => __('All podcasts'),
                        'route' => $this->route('wnip.podcasts.index'),
                        'type' => 'primary',
                    ],
                ]),
            ],
            [
                'key' => 'interviews',
                'eyebrow' => __('Interviews'),
                'title' => __('Scheduled interviews & scorecards'),
                'description' => __('Interactive interview rooms with scoring, notes, and Utilities reminders.'),
                'items' => $interviews->toArray(),
                'actions' => array_filter([
                    [
                        'label' => __('Interview schedule'),
                        'route' => $this->route('wnip.interviews.index'),
                        'type' => 'primary',
                    ],
                ]),
            ],
        ];
    }

    protected function webinarsQuery(): Builder
    {
        return Webinar::query()
            ->with(['host'])
            ->withCount('registrations');
    }

    protected function upcomingWebinars(int $limit = 3): Collection
    {
        return $this->webinarsQuery()
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            })
            ->orderBy('starts_at')
            ->limit($limit)
            ->get();
    }

    protected function upcomingNetworking(int $limit = 3): Collection
    {
        return NetworkingSession::query()
            ->withCount('participants')
            ->orderBy('starts_at')
            ->limit($limit)
            ->get();
    }

    protected function latestPodcastEpisodes(int $limit = 3): Collection
    {
        return PodcastEpisode::query()
            ->with('series')
            ->whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }

    protected function upcomingInterviews(int $limit = 3): Collection
    {
        return Interview::query()
            ->with(['host'])
            ->orderBy('scheduled_at')
            ->limit($limit)
            ->get();
    }

    protected function formatRecommendation(string $title, ?string $subtitle, ?int $startsAt, ?string $href): array
    {
        $time = $startsAt
            ? now()->copy()->setTimestamp($startsAt)->diffForHumans()
            : null;

        return [
            'title' => Str::limit($title, 64),
            'location' => $subtitle ?? __('Live session'),
            'time' => $time,
            'link' => $href ?? url('/live-center/hub'),
            'sort_key' => $startsAt ?? PHP_INT_MAX,
        ];
    }

    protected function route(string $name, $model = null)
    {
        if (! $name || ! Route::has($name)) {
            return null;
        }

        return $model ? route($name, $model) : route($name);
    }
}


