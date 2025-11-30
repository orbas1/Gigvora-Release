<?php

namespace App\Support\Feed;

use App\Models\Posts;
use Illuminate\Support\Arr;

class FeedCardPresenter
{
    protected array $definitions = [
        'job' => [
            'eyebrow' => 'Job opportunity',
            'icon' => 'fa-solid fa-briefcase',
            'badge' => 'gv-chip--primary',
            'cta_label' => 'View role',
        ],
        'freelance_project' => [
            'eyebrow' => 'Freelance project',
            'icon' => 'fa-solid fa-handshake-angle',
            'badge' => 'gv-chip--primary',
            'cta_label' => 'View project',
        ],
        'gig' => [
            'eyebrow' => 'Gig offer',
            'icon' => 'fa-solid fa-sparkles',
            'badge' => 'gv-chip--primary',
            'cta_label' => 'Open gig',
        ],
        'live_streaming' => [
            'eyebrow' => 'Live session',
            'icon' => 'fa-solid fa-podcast',
            'badge' => 'gv-chip--danger',
            'cta_label' => 'Join live shell',
        ],
        'event' => [
            'eyebrow' => 'Event update',
            'icon' => 'fa-solid fa-calendar-days',
            'badge' => 'gv-chip--primary',
            'cta_label' => 'View event',
        ],
        'interview' => [
            'eyebrow' => 'Interview update',
            'icon' => 'fa-solid fa-user-tie',
            'badge' => 'gv-chip--primary',
            'cta_label' => 'Open interview',
        ],
        'utilities_alert' => [
            'eyebrow' => 'Utilities alert',
            'icon' => 'fa-solid fa-bolt',
            'badge' => 'gv-chip--warning',
            'cta_label' => 'Open utilities hub',
            'cta_route' => 'utilities.hub',
        ],
        'sponsored' => [
            'eyebrow' => 'Sponsored',
            'icon' => 'fa-solid fa-bullhorn',
            'badge' => 'gv-chip--warning',
            'cta_label' => 'Learn more',
        ],
        'default' => [
            'eyebrow' => null,
            'icon' => 'fa-regular fa-pen-to-square',
            'badge' => 'gv-chip--primary',
            'cta_label' => 'Open post',
        ],
    ];

    public function present(Posts $post): array
    {
        $type = $post->post_type ?? 'default';
        $definition = $this->definitionFor($type);

        return [
            'type' => $type,
            'eyebrow' => $this->translated($definition['eyebrow']),
            'icon' => $definition['icon'],
            'badge' => $definition['badge'],
            'cta_label' => $this->translated($definition['cta_label']),
            'cta_url' => $this->ctaUrl($post, $definition),
            'summary' => $this->summary($post),
        ];
    }

    protected function definitionFor(string $type): array
    {
        if (in_array($type, ['freelance_project', 'gig'], true)) {
            return $this->definitions['freelance_project'];
        }

        return $this->definitions[$type] ?? $this->definitions['default'];
    }

    protected function translated(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        return function_exists('get_phrase') ? get_phrase($value) : __($value);
    }

    protected function summary(Posts $post): ?string
    {
        if (empty($post->description)) {
            return null;
        }

        $plain = strip_tags($post->description);

        return function_exists('ellipsis') ? ellipsis($plain, 160) : mb_substr($plain, 0, 160);
    }

    protected function ctaUrl(Posts $post, array $definition): string
    {
        $route = Arr::get($definition, 'cta_route');
        if ($route && function_exists('route') && \Route::has($route)) {
            return route($route, Arr::get($definition, 'cta_route_params', []));
        }

        return route('single.post', $post->post_id);
    }
}

