<?php

namespace App\Services;

use App\Models\Group;
use App\Models\Group_member;
use App\Models\Marketplace;
use App\Models\Page;
use App\Models\Page_like;
use App\Models\Posts;
use App\Models\SavedProduct;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class CommunitySurfaceService
{
    public function pagePanels(Page $page): array
    {
        $base = $this->basePublisherQuery('page', $page->id);

        $jobs = $this->collectPosts(clone $base, ['job']);
        $gigs = $this->collectPosts(clone $base, ['freelance_project', 'gig']);
        $events = $this->collectPosts(clone $base, ['event', 'live_streaming']);

        $followerCount = Page_like::where('page_id', $page->id)->count();
        $postCount = (clone $base)->count();

        return [
            'stats' => [
                'followers' => $followerCount,
                'posts' => $postCount,
                'jobs' => count($jobs),
                'events' => count($events),
            ],
            'jobs' => $jobs,
            'gigs' => $gigs,
            'events' => $events,
            'analytics' => [
                [
                    'label' => get_phrase('Follower growth'),
                    'value' => number_format($followerCount),
                    'description' => get_phrase('Total followers across Gigvora'),
                ],
                [
                    'label' => get_phrase('Published posts'),
                    'value' => number_format($postCount),
                    'description' => get_phrase('Includes feed + job announcements'),
                ],
                [
                    'label' => get_phrase('Active hiring needs'),
                    'value' => number_format(count($jobs)),
                    'description' => get_phrase('Jobs linked to this page'),
                ],
                [
                    'label' => get_phrase('Upcoming promos'),
                    'value' => number_format(count($events)),
                    'description' => get_phrase('Events & live streams cross-posted'),
                ],
            ],
            'utilities' => $this->utilitiesShortcuts(),
        ];
    }

    public function groupPanels(Group $group): array
    {
        $base = $this->basePublisherQuery('group', $group->id);
        $members = Group_member::where('group_id', $group->id)->where('is_accepted', 1)->count();

        $jobs = $this->collectPosts(clone $base, ['job']);
        $gigs = $this->collectPosts(clone $base, ['freelance_project', 'gig']);
        $events = $this->collectPosts(clone $base, ['event']);

        return [
            'stats' => [
                'members' => $members,
                'posts' => (clone $base)->count(),
                'jobs' => count($jobs),
                'events' => count($events),
            ],
            'jobs' => $jobs,
            'gigs' => $gigs,
            'events' => $events,
            'analytics' => [
                [
                    'label' => get_phrase('Membership'),
                    'value' => number_format($members),
                    'description' => get_phrase('Approved members in this community'),
                ],
                [
                    'label' => get_phrase('Hiring threads'),
                    'value' => number_format(count($jobs) + count($gigs)),
                    'description' => get_phrase('Jobs & gigs shared into this group'),
                ],
                [
                    'label' => get_phrase('Event momentum'),
                    'value' => number_format(count($events)),
                    'description' => get_phrase('Upcoming group events'),
                ],
            ],
            'utilities' => $this->utilitiesShortcuts(),
        ];
    }

    public function marketplacePanels(?int $userId = null): array
    {
        $query = Marketplace::query();
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('status', 1);
        }

        $products = $query->latest('id')->limit(8)->get();
        $saved = $userId
            ? SavedProduct::where('user_id', $userId)->count()
            : SavedProduct::count();

        $activeListings = (clone $query)->count();
        $newThisWeek = (clone $query)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        return [
            'highlights' => $products->map(function (Marketplace $product) {
                return [
                    'id' => $product->id,
                    'title' => Str::limit($product->title ?? '', 48),
                    'price' => $product->price,
                    'currency' => optional($product->getCurrency)->symbol,
                    'location' => $product->location ?? get_phrase('Global'),
                    'link' => route('single.product', $product->id),
                ];
            })->toArray(),
            'analytics' => [
                [
                    'label' => get_phrase('Active listings'),
                    'value' => number_format($activeListings),
                    'description' => $userId
                        ? get_phrase('Products managed by you')
                        : get_phrase('Live items across Gigvora'),
                ],
                [
                    'label' => get_phrase('Added this week'),
                    'value' => number_format($newThisWeek),
                    'description' => get_phrase('Fresh inventory in the last 7 days'),
                ],
                [
                    'label' => get_phrase('Saved items'),
                    'value' => number_format($saved),
                    'description' => $userId
                        ? get_phrase('Products you bookmarked')
                        : get_phrase('Total saves across buyers'),
                ],
            ],
            'utilities' => [
                [
                    'icon' => 'fa-solid fa-bell',
                    'label' => get_phrase('Restock alert'),
                    'href' => Route::has('utilities.hub') ? route('utilities.hub') . '#alerts' : url('/utilities/hub'),
                ],
                [
                    'icon' => 'fa-solid fa-calendar-check',
                    'label' => get_phrase('Schedule demo'),
                    'href' => Route::has('utilities.hub') ? route('utilities.hub') . '#calendar' : url('/utilities/hub'),
                ],
                [
                    'icon' => 'fa-solid fa-shield',
                    'label' => get_phrase('Dispute center'),
                    'href' => Route::has('utilities.moderation') ? route('utilities.moderation') : url('/utilities/hub'),
                ],
            ],
        ];
    }

    protected function basePublisherQuery(string $publisher, int $id): Builder
    {
        return Posts::query()
            ->where('publisher', $publisher)
            ->where('publisher_id', $id)
            ->where('status', 'active');
    }

    protected function collectPosts(Builder $builder, array $types, int $limit = 5): array
    {
        return $builder
            ->whereIn('post_type', $types)
            ->latest('post_id')
            ->limit($limit)
            ->get()
            ->map(function (Posts $post) {
                return [
                    'id' => $post->post_id,
                    'title' => Str::limit(strip_tags($post->description ?? ''), 120),
                    'type' => strtoupper(str_replace('_', ' ', $post->post_type ?? 'post')),
                    'meta' => $this->formatTimestamp($post->created_at),
                    'link' => route('single.post', $post->post_id),
                ];
            })
            ->toArray();
    }

    protected function formatTimestamp($value): string
    {
        $timestamp = $this->resolveTimestamp($value);

        return $timestamp ? Carbon::createFromTimestamp($timestamp)->diffForHumans() : Carbon::now()->diffForHumans();
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

        return $parsed ?: null;
    }

    protected function utilitiesShortcuts(): array
    {
        return [
            [
                'icon' => 'fa-solid fa-bell',
                'label' => get_phrase('Schedule reminder'),
                'href' => Route::has('utilities.hub') ? route('utilities.hub') . '#calendar' : url('/utilities/hub'),
            ],
            [
                'icon' => 'fa-solid fa-hand-holding-dollar',
                'label' => get_phrase('Boost post / ad'),
                'href' => Route::has('advertisement.dashboard') ? route('advertisement.dashboard') : url('/addons/advertisement'),
            ],
            [
                'icon' => 'fa-solid fa-chart-line',
                'label' => get_phrase('View analytics'),
                'href' => Route::has('utilities.analytics') ? route('utilities.analytics') : url('/utilities/hub'),
            ],
        ];
    }
}


