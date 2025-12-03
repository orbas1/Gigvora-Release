<?php

namespace App\Services;

use App\Models\Friendships;
use App\Models\Posts;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SearchRankingService
{
    public function __construct(protected KeywordRegistryService $keywords)
    {
    }

    public function rankUsers(Collection $users, ?User $viewer, ?string $query = null): Collection
    {
        $weights = config('search.weights', []);

        return $users
            ->map(function (User $user) use ($weights, $viewer, $query) {
                $score = 0;
                $score += ($user->followers ?? 0) * ($weights['followers'] ?? 1);
                $score += $this->connectionScore($user, $viewer) * ($weights['connections'] ?? 1);
                $score += $this->recentScore($user->lastActive ?? null) * ($weights['recent_activity'] ?? 1);
                $score += $this->countryScore($user->address ?? null, $viewer) * ($weights['country_match'] ?? 1);
                $score += $this->vipScore($user) * ($weights['vip'] ?? 1);
                $score += $this->offeringScore($user, $query) * ($weights['offerings'] ?? 1);

                $user->ranking_score = $score;

                return $user;
            })
            ->sortByDesc('ranking_score')
            ->values();
    }

    public function rankPosts(Collection $posts, ?User $viewer, ?string $query = null): Collection
    {
        $weights = config('search.weights', []);

        return $posts
            ->map(function (Posts $post) use ($viewer, $weights, $query) {
                $score = 0;
                $score += $this->recentScore($post->updated_at ?? $post->created_at) * ($weights['recent_activity'] ?? 1);
                $score += $this->countryScore($post->location ?? null, $viewer) * ($weights['country_match'] ?? 1);
                $score += $this->vipScore($post->getUser?->first()) * ($weights['vip'] ?? 1);
                $score += $this->hashtagScore($post->hashtag ?? '', $query) * ($weights['offerings'] ?? 1);

                $post->ranking_score = $score;

                return $post;
            })
            ->sortByDesc('ranking_score')
            ->values();
    }

    public function rankGeneric(Collection $items, callable $scoreCallback): Collection
    {
        return $items
            ->map(function ($item) use ($scoreCallback) {
                $item->ranking_score = $scoreCallback($item);

                return $item;
            })
            ->sortByDesc('ranking_score')
            ->values();
    }

    protected function connectionScore(User $user, ?User $viewer): float
    {
        if (! $viewer) {
            return 0;
        }

        $cacheKey = sprintf('connections:%d:%d', $viewer->id, $user->id);

        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($user, $viewer) {
            return Friendships::query()
                ->where('is_accepted', 1)
                ->where(function ($query) use ($viewer, $user) {
                    $query
                        ->where(function ($sub) use ($viewer, $user) {
                            $sub->where('requester', $viewer->id)->where('accepter', $user->id);
                        })
                        ->orWhere(function ($sub) use ($viewer, $user) {
                            $sub->where('requester', $user->id)->where('accepter', $viewer->id);
                        });
                })
                ->count() > 0 ? 1 : 0.3;
        });
    }

    protected function recentScore($lastSeen): float
    {
        if (! $lastSeen) {
            return 0;
        }

        $lastSeenAt = is_string($lastSeen) ? now()->parse($lastSeen) : $lastSeen;
        $minutes = max(now()->diffInMinutes($lastSeenAt), 1);

        return max(0.1, 5 / $minutes);
    }

    protected function countryScore(?string $country, ?User $viewer): float
    {
        if (! $viewer || ! $country) {
            return 0;
        }

        $viewerCountry = $viewer->address ?? '';

        return Str::contains(Str::lower($viewerCountry), Str::lower($country)) ? 1 : 0;
    }

    protected function vipScore(?User $user): float
    {
        if (! $user) {
            return 0;
        }

        $role = Str::lower((string) $user->user_role);

        return Str::contains($role, 'vip') || Str::contains($role, 'verified') ? 1 : 0;
    }

    protected function offeringScore(User $user, ?string $query): float
    {
        if (! $query) {
            return 0.2;
        }

        $keywords = $this->keywords->relatedKeywords([$query])->pluck('keyword');
        $match = $keywords->first(function ($keyword) use ($user) {
            return Str::contains(Str::lower($user->profession ?? ''), Str::lower($keyword));
        });

        return $match ? 1 : 0.2;
    }

    protected function hashtagScore(string $hashtagString, ?string $query): float
    {
        if (! $query) {
            return $hashtagString ? 0.4 : 0;
        }

        $normalizedQuery = Str::lower(ltrim($query, '#'));
        $tags = collect(explode(',', Str::of($hashtagString)->replace('#', '')->value()))
            ->map(fn ($tag) => Str::lower(trim($tag)))
            ->filter();

        return $tags->contains($normalizedQuery) ? 1 : ($tags->isNotEmpty() ? 0.3 : 0);
    }
}
