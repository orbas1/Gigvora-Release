<?php

namespace App\Services;

use App\Models\Gig\Gig;
use App\Models\Profile;
use App\Models\Project;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class FreelanceSearchService
{
    /**
     * Return highlighted projects that match an optional query.
     */
    public function highlightedProjects(?string $query = null, int $limit = 6): array
    {
        $cacheKey = sprintf('freelance-projects:%s:%d', md5($query ?? 'all'), $limit);

        return Cache::tags(['freelance', 'search'])->remember($cacheKey, now()->addMinutes(5), function () use ($query, $limit) {
            return Project::query()
                ->select([
                    'id',
                    'project_title',
                    'slug',
                    'project_description',
                    'project_min_price',
                    'project_max_price',
                    'project_type',
                    'project_country',
                    'status',
                    'author_id',
                    'updated_at',
                ])
                ->with('projectAuthor:id,first_name,last_name')
                ->when($query, function ($builder) use ($query) {
                    $builder
                        ->whereFullText('project_title', $query)
                        ->orWhereFullText('project_description', $query);
                })
                ->whereIn('status', ['publish', 'hired'])
                ->latest('updated_at')
                ->limit($limit)
                ->get()
                ->map(function (Project $project) {
                    return [
                        'id' => $project->id,
                        'title' => $project->project_title,
                        'summary' => ellipsis(strip_tags((string) $project->project_description), 140),
                        'description' => strip_tags((string) $project->project_description),
                        'budget' => $this->formatBudget($project->project_min_price, $project->project_max_price),
                        'type' => strtoupper($project->project_type ?? 'fixed'),
                        'location' => $project->project_country ?? get_phrase('Remote'),
                        'owner' => optional($project->projectAuthor)->full_name ?? get_phrase('Client'),
                        'link' => route('freelance.projects.detail', $project->slug),
                    ];
                })
                ->toArray();
        });
    }

    /**
     * Return highlighted gigs.
     */
    public function highlightedGigs(?string $query = null, int $limit = 6): array
    {
        $cacheKey = sprintf('freelance-gigs:%s:%d', md5($query ?? 'all'), $limit);

        return Cache::tags(['freelance', 'search'])->remember($cacheKey, now()->addMinutes(5), function () use ($query, $limit) {
            return Gig::query()
                ->select([
                    'id',
                    'title',
                    'slug',
                    'description',
                    'status',
                    'delivery_time',
                    'author_id',
                    'price',
                    'price_type',
                    'updated_at',
                ])
                ->with('gigAuthor:id,first_name,last_name')
                ->when($query, function ($builder) use ($query) {
                    $builder
                        ->whereFullText('title', $query)
                        ->orWhereFullText('description', $query);
                })
                ->where('status', 'publish')
                ->latest('updated_at')
                ->limit($limit)
                ->get()
                ->map(function (Gig $gig) {
                    return [
                        'id' => $gig->id,
                        'title' => $gig->title,
                        'summary' => ellipsis(strip_tags((string) $gig->description), 120),
                        'description' => strip_tags((string) $gig->description),
                        'owner' => optional($gig->gigAuthor)->full_name ?? get_phrase('Freelancer'),
                        'price' => $this->formatBudget($gig->price, $gig->price),
                        'delivery' => $gig->delivery_time ? get_phrase('{:days} days', ['days' => $gig->delivery_time]) : null,
                        'link' => route('freelance.gigs.detail', $gig->slug),
                    ];
                })
                ->toArray();
        });
    }

    /**
     * Return highlighted freelancer profiles.
     */
    public function highlightedTalent(?string $query = null, int $limit = 6): array
    {
        $cacheKey = sprintf('freelance-talent:%s:%d', md5($query ?? 'all'), $limit);

        return Cache::tags(['freelance', 'search'])->remember($cacheKey, now()->addMinutes(10), function () use ($query, $limit) {
            return Profile::query()
                ->select(['id', 'slug', 'first_name', 'last_name', 'tagline', 'description'])
                ->with('skills:id,name')
                ->whereHas('role', function ($builder) {
                    $builder->where('name', config('freelance.roles.seller', 'seller'));
                })
                ->when($query, function ($builder) use ($query) {
                    $builder
                        ->whereFullText('first_name', $query)
                        ->orWhereFullText('last_name', $query)
                        ->orWhereFullText('tagline', $query)
                        ->orWhereFullText('description', $query);
                })
                ->latest('updated_at')
                ->limit($limit)
                ->get()
                ->map(function (Profile $profile) {
                    return [
                        'id' => $profile->id,
                        'name' => $profile->full_name,
                        'tagline' => $profile->tagline ?? get_phrase('Freelance professional'),
                        'skills' => $profile->skills->pluck('name')->take(4)->implode(', '),
                        'link' => route('freelance.sellers.profile', $profile->slug),
                    ];
                })
                ->toArray();
        });
    }

    protected function formatBudget(?float $min, ?float $max): string
    {
        $currency = setting('_general.currency') ?? 'USD';
        $minValue = $min ?? 0;
        $maxValue = $max ?? $minValue;

        if ($maxValue <= 0 || Str::startsWith((string) $maxValue, '-')) {
            return $currency.' '.number_format($minValue, 2);
        }

        if ($minValue === $maxValue) {
            return $currency.' '.number_format($minValue, 2);
        }

        return sprintf('%s %s – %s', $currency, number_format($minValue, 0), number_format($maxValue, 0));
    }
}


