<?php

namespace App\Services;

use App\Models\Posts;
use App\Models\User;
use Illuminate\Support\Carbon;

class FeedRecommendationService
{
    public function __construct(
        protected FreelanceSearchService $freelanceSearch,
        protected LiveEventsExperienceService $liveExperience,
        protected TalentAiInsightsService $talentAi,
    ) {
    }

    public function forUser(User $user): array
    {
        return [
            'people' => $this->recommendedPeople($user),
            'jobs' => $this->recommendedJobs(),
            'freelance' => $this->recommendedFreelance(),
            'live' => $this->recommendedLive(),
            'utilities' => $this->utilitiesShortcuts(),
            'talent_ai' => $this->talentAi->recommendationLanes($user),
        ];
    }

    protected function recommendedPeople(User $user): array
    {
        $friendIds = array_keys(json_decode($user->friends ?? '{}', true) ?? []);
        $friendIds[] = $user->id;

        return User::query()
            ->whereNotIn('id', $friendIds)
            ->where('status', 1)
            ->latest('id')
            ->limit(5)
            ->get(['id', 'name', 'photo', 'about'])
            ->map(function (User $person) {
                return [
                    'id' => $person->id,
                    'name' => $person->name,
                    'photo' => get_user_image($person->photo, 'optimized'),
                    'about' => $person->about ? ellipsis($person->about, 60) : get_phrase('Active on Gigvora'),
                    'profile_url' => route('user.profile.view', $person->id),
                ];
            })
            ->toArray();
    }

    protected function recommendedJobs(): array
    {
        return Posts::query()
            ->where('post_type', 'job')
            ->where('status', 'active')
            ->latest('post_id')
            ->limit(3)
            ->get(['post_id', 'description', 'user_id', 'created_at'])
            ->map(function (Posts $post) {
                $company = $post->getUser->name ?? get_phrase('Company');
                $timestamp = $this->resolveTimestamp($post->created_at);
                $postedAt = $timestamp ? Carbon::createFromTimestamp($timestamp)->diffForHumans() : null;

                return [
                    'id' => $post->post_id,
                    'title' => ellipsis(strip_tags($post->description), 70),
                    'company' => $company,
                    'posted_at' => $postedAt,
                    'link' => route('single.post', $post->post_id),
                ];
            })
            ->toArray();
    }

    protected function recommendedFreelance(): array
    {
        $projects = $this->freelanceSearch->highlightedProjects(null, 3);
        $gigs = $this->freelanceSearch->highlightedGigs(null, 3);

        return collect($projects)
            ->merge($gigs)
            ->take(4)
            ->map(function (array $item) {
                return [
                    'title' => $item['title'],
                    'owner' => $item['owner'] ?? get_phrase('Freelancer'),
                    'type' => $item['type'] ?? get_phrase('Gig'),
                    'meta' => $item['budget'] ?? $item['price'] ?? null,
                    'link' => $item['link'],
                ];
            })
            ->values()
            ->toArray();
    }

    protected function recommendedLive(): array
    {
        $cards = $this->liveExperience->recommendationCards();

        if (! empty($cards)) {
            return $cards;
        }

        return $this->legacyLiveRecommendations();
    }

    protected function legacyLiveRecommendations(int $limit = 3): array
    {
        return Posts::query()
            ->whereIn('post_type', ['live_streaming', 'event'])
            ->latest('post_id')
            ->limit($limit)
            ->get(['post_id', 'description', 'created_at', 'location'])
            ->map(function (Posts $post) {
                $timestamp = $this->resolveTimestamp($post->created_at);
                $timeLabel = $timestamp ? Carbon::createFromTimestamp($timestamp)->diffForHumans() : null;

                return [
                    'id' => $post->post_id,
                    'title' => ellipsis(strip_tags($post->description), 60),
                    'time' => $timeLabel,
                    'location' => $post->location ?: get_phrase('Online'),
                    'link' => route('single.post', $post->post_id),
                ];
            })
            ->toArray();
    }

    protected function utilitiesShortcuts(): array
    {
        return [
            [
                'label' => get_phrase('Plan taxes'),
                'icon' => 'fa-solid fa-calculator',
                'url' => url('#utilities-taxes'),
            ],
            [
                'label' => get_phrase('Track expenses'),
                'icon' => 'fa-solid fa-coins',
                'url' => url('#utilities-expenses'),
            ],
            [
                'label' => get_phrase('Security checkup'),
                'icon' => 'fa-solid fa-shield-halved',
                'url' => url('#utilities-security'),
            ],
        ];
    }

    protected function resolveTimestamp($value): ?int
    {
        if (empty($value)) {
            return null;
        }

        if (is_numeric($value)) {
            return (int) $value;
        }

        $parsed = strtotime((string) $value);

        return $parsed ? (int) $parsed : null;
    }
}

