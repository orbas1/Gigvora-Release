<?php

namespace Advertisement\Services;

use Advertisement\Models\Campaign;
use Advertisement\Models\KeywordPrice;
use Advertisement\Models\Metric;
use Advertisement\Models\TargetingRule;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class BidStrategyService
{
    public function __construct(private readonly CacheRepository $cache)
    {
    }

    public function priceKeyword(string $keyword): KeywordPrice
    {
        $normalized = $this->normalize($keyword);
        $payload = $this->cache->remember(
            "gigvora-ads-keyword-{$normalized}",
            now()->addMinutes(15),
            fn () => $this->calculatePayload($normalized)
        );

        return tap(
            KeywordPrice::updateOrCreate(
                ['keyword' => $normalized],
                array_merge($payload, ['last_synced_at' => now()])
            )
        );
    }

    protected function calculatePayload(string $keyword): array
    {
        $stats = $this->keywordStats($keyword);
        $baseline = $this->baseline($keyword);

        $searchVolume = max(
            $baseline['volume'],
            (int) round(($stats['impressions'] * 0.35) + ($baseline['volume'] * 0.65))
        );

        $competitionScore = $this->clamp(
            ($baseline['competition'] + $stats['competition']) / 2,
            0,
            1
        );

        $qualityScore = $this->clamp(
            (0.6 * $stats['ctr']) + (0.4 * $stats['conversion_rate']) + 0.15,
            0.25,
            0.98
        );

        $placementMultiplier = $stats['placement_multiplier'] ?: $baseline['placement_multiplier'];

        $baseCpc = config('advertisement.bidding.click_cost', 0.65);
        $baseCpm = config('advertisement.bidding.view_cost', 8.00);
        $baseCpa = config('advertisement.bidding.conversion_cost', 9.25);

        $pricingPressure = 1 + ($competitionScore * 0.85);
        $qualityDiscount = 1 - ($qualityScore * 0.3);
        $volumeBoost = 1 + min(0.45, $searchVolume / 60000);

        $cpc = round(
            max(0.12, $baseCpc * $pricingPressure * $qualityDiscount * $placementMultiplier),
            2
        );

        $cpm = round(
            max(1.25, $baseCpm * $pricingPressure * $volumeBoost * ($placementMultiplier * 0.85)),
            2
        );

        $conversionRate = max(0.01, $stats['conversion_rate'] ?: 0.015);
        $cpa = round(
            max(
                1.00,
                $baseCpa * ($competitionScore + 0.5) / $conversionRate * (1 - $qualityScore / 3)
            ),
            2
        );

        return [
            'search_volume' => $searchVolume,
            'competition_score' => $competitionScore,
            'quality_score' => $qualityScore,
            'ctr' => $stats['ctr'],
            'conversion_rate' => $conversionRate,
            'placement_multiplier' => $placementMultiplier,
            'currency' => config('app.currency', 'USD'),
            'cpc' => $cpc,
            'cpm' => $cpm,
            'cpa' => $cpa,
        ];
    }

    /**
     * @return array{
     *     impressions: float,
     *     ctr: float,
     *     conversion_rate: float,
     *     competition: float,
     *     placement_multiplier: float
     * }
     */
    protected function keywordStats(string $keyword): array
    {
        $campaignIds = TargetingRule::query()
            ->where('type', 'keyword')
            ->whereRaw('LOWER(value) = ?', [$keyword])
            ->pluck('campaign_id');

        if ($campaignIds->isEmpty()) {
            return [
                'impressions' => 0,
                'ctr' => 0.02,
                'conversion_rate' => 0.015,
                'competition' => 0,
                'placement_multiplier' => 1,
            ];
        }

        $metrics = Metric::query()
            ->whereIn('campaign_id', $campaignIds)
            ->selectRaw('SUM(impressions) as impressions')
            ->selectRaw('SUM(clicks) as clicks')
            ->selectRaw('SUM(conversions) as conversions')
            ->first();

        $impressions = (float) ($metrics->impressions ?? 0);
        $clicks = (float) ($metrics->clicks ?? 0);
        $conversions = (float) ($metrics->conversions ?? 0);

        $ctr = $impressions > 0 ? $clicks / $impressions : 0.02;
        $conversionRate = $clicks > 0 ? $conversions / max($clicks, 1) : 0.015;

        $activeCampaigns = Campaign::query()
            ->whereIn('id', $campaignIds)
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->count();

        $competition = $this->clamp($activeCampaigns / 12, 0, 1);

        $placementMultiplier = $this->placementMultiplierFromCampaigns($campaignIds);

        return [
            'impressions' => $impressions,
            'ctr' => $ctr,
            'conversion_rate' => $conversionRate,
            'competition' => $competition,
            'placement_multiplier' => $placementMultiplier,
        ];
    }

    protected function placementMultiplierFromCampaigns(Collection $campaignIds): float
    {
        $placements = Campaign::query()
            ->whereIn('id', $campaignIds)
            ->pluck('placement');

        if ($placements->isEmpty()) {
            return 1.0;
        }

        return $placements
            ->map(fn ($placement) => $this->placementMultiplier($placement))
            ->avg() ?? 1.0;
    }

    protected function placementMultiplier(string $placement): float
    {
        $config = config("advertisement.placements.{$placement}");

        return (float) ($config['pricing_multiplier'] ?? 1.0);
    }

    protected function baseline(string $keyword): array
    {
        $hash = crc32($keyword);

        return [
            'volume' => 800 + ($hash % 40000),
            'competition' => (($hash >> 8) & 255) / 255,
            'placement_multiplier' => 0.9 + ((($hash >> 16) & 255) / 255) * 0.8,
        ];
    }

    protected function normalize(string $keyword): string
    {
        return Str::of($keyword)->lower()->trim()->value();
    }

    protected function clamp(float $value, float $min, float $max): float
    {
        return min($max, max($min, $value));
    }
}

