<?php

namespace App\Services;

use Advertisement\Models\Campaign;
use Advertisement\Models\Creative;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Fluent;
use Illuminate\Support\Str;

class AdvertisementSurfaceService
{
    public function forSlot(string $slot): ?Fluent
    {
        if (!config('advertisement.enabled') || !class_exists(Campaign::class)) {
            return null;
        }

        $placement = config("advertisement.placements.{$slot}");

        if (! $placement) {
            return null;
        }

        return Cache::remember("advertisement-slot-{$slot}", now()->addMinutes(5), function () use ($slot, $placement) {
            $candidates = Campaign::query()
                ->where('status', 'active')
                ->where('placement', $slot)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->with(['creatives' => function ($query) use ($placement) {
                    $query->where('status', 'active');

                    if (! empty($placement['creative_types'])) {
                        $query->whereIn('type', $placement['creative_types']);
                    }
                }])
                ->withSum('metrics as impressions_sum', 'impressions')
                ->withSum('metrics as clicks_sum', 'clicks')
                ->withSum('metrics as conversions_sum', 'conversions')
                ->withSum('metrics as spend_sum', 'spend')
                ->limit(15)
                ->get()
                ->map(function (Campaign $campaign) use ($placement) {
                    $creative = $campaign->creatives->first();

                    if (! $creative) {
                        return null;
                    }

                    return [
                        'score' => $this->scoreCampaign($campaign, $placement),
                        'campaign' => $campaign,
                        'creative' => $creative,
                    ];
                })
                ->filter()
                ->sortByDesc('score')
                ->first();

            if (! $candidates) {
                return null;
            }

            return new Fluent(
                $this->transformCreative(
                    $candidates['creative'],
                    $candidates['campaign'],
                    $placement,
                    $candidates['score']
                )
            );
        });
    }

    protected function scoreCampaign(Campaign $campaign, array $placement): float
    {
        $impressions = max((float) ($campaign->impressions_sum ?? 0), 1);
        $clicks = (float) ($campaign->clicks_sum ?? 0);
        $conversions = (float) ($campaign->conversions_sum ?? 0);
        $spend = (float) ($campaign->spend_sum ?? 0);

        $ctr = $clicks / $impressions;
        $cvr = $clicks > 0 ? $conversions / $clicks : 0;
        $pacing = 1 - min(1, $spend / max((float) $campaign->budget, 1));

        $duration = max($campaign->start_date->diffInHours($campaign->end_date), 1);
        $age = now()->diffInHours($campaign->start_date);
        $freshness = 1 - min(1, $age / $duration);

        $diversitySeed = fmod(crc32($campaign->id . $placement['label']), 1000) / 1000;

        $weights = array_merge([
            'ctr' => 0.3,
            'conversion' => 0.2,
            'pacing' => 0.2,
            'freshness' => 0.2,
            'diversity' => 0.1,
        ], $placement['weights'] ?? []);

        return
            ($weights['ctr'] ?? 0.3) * $ctr +
            ($weights['conversion'] ?? 0.2) * $cvr +
            ($weights['pacing'] ?? 0.2) * $pacing +
            ($weights['freshness'] ?? 0.2) * $freshness +
            ($weights['diversity'] ?? 0.1) * $diversitySeed;
    }

    protected function transformCreative(Creative $creative, Campaign $campaign, array $placement, float $score): array
    {
        $impressions = max((float) ($campaign->impressions_sum ?? 0), 1);
        $clicks = (float) ($campaign->clicks_sum ?? 0);
        $conversions = (float) ($campaign->conversions_sum ?? 0);

        $ctr = $clicks / $impressions;
        $cvr = $clicks > 0 ? $conversions / $clicks : 0;

        $destination = $creative->destination_url ?? url('/');
        $displayUrl = parse_url($destination, PHP_URL_HOST) ?? Str::of($destination)->limit(32);

        return [
            'id' => $creative->id,
            'campaign_id' => $campaign->id,
            'title' => $creative->title ?? $campaign->title,
            'description' => $creative->body ?? __('Promote your project to the Gigvora network.'),
            'cta' => $creative->cta ?? __('Learn more'),
            'url' => $destination,
            'display_url' => $displayUrl,
            'status' => $campaign->approval_state ?? $campaign->status,
            'type' => $creative->type,
            'media_url' => $this->mediaUrl($creative->media_path),
            'placement' => $campaign->placement,
            'component' => $placement['component'] ?? 'advertisement::components.ad_feed_card',
            'score' => $score,
            'metrics' => [
                'ctr' => round($ctr * 100, 2) . '%',
                'cvr' => round($cvr * 100, 2) . '%',
            ],
        ];
    }

    protected function mediaUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        if (Str::startsWith($path, 'storage/')) {
            return asset($path);
        }

        if (Storage::disk(config('filesystems.default'))->exists($path)) {
            return Storage::disk(config('filesystems.default'))->url($path);
        }

        return asset($path);
    }
}

