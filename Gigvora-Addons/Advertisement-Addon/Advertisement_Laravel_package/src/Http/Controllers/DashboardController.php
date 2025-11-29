<?php

declare(strict_types=1);

namespace Advertisement\Http\Controllers;

use Advertisement\Models\Campaign;
use Advertisement\Models\Metric;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DashboardController
{
    public function __invoke(Request $request): JsonResponse
    {
        [$from, $to] = $this->parseRange($request->string('range')->toString());

        $metricsQuery = Metric::query()->whereBetween('recorded_at', [$from, $to]);

        $totals = [
            'spend' => (clone $metricsQuery)->sum('spend'),
            'impressions' => (clone $metricsQuery)->sum('impressions'),
            'clicks' => (clone $metricsQuery)->sum('clicks'),
            'conversions' => (clone $metricsQuery)->sum('conversions'),
        ];

        $timeseries = (clone $metricsQuery)
            ->selectRaw('DATE(recorded_at) as day')
            ->selectRaw('SUM(spend) as spend')
            ->selectRaw('SUM(impressions) as impressions')
            ->selectRaw('SUM(clicks) as clicks')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $topCampaigns = Campaign::query()
            ->withSum('metrics as impressions_sum', 'impressions')
            ->withSum('metrics as clicks_sum', 'clicks')
            ->withSum('metrics as spend_sum', 'spend')
            ->orderByDesc('spend_sum')
            ->limit(5)
            ->get()
            ->map(function (Campaign $campaign): array {
                $ctr = $campaign->impressions_sum > 0
                    ? round(($campaign->clicks_sum / $campaign->impressions_sum) * 100, 2) . '%'
                    : '0%';

                return [
                    'id' => $campaign->id,
                    'name' => $campaign->title,
                    'status' => ucfirst($campaign->status ?? 'active'),
                    'impressions' => number_format((int) $campaign->impressions_sum),
                    'clicks' => number_format((int) $campaign->clicks_sum),
                    'ctr' => $ctr,
                    'spend' => '$' . number_format((float) $campaign->spend_sum, 2),
                ];
            });

        return response()->json([
            'kpis' => $this->formatKpis($totals),
            'labels' => $timeseries->pluck('day')->map(fn ($day) => Carbon::parse($day)->format('M d')),
            'series' => $this->formatSeries($timeseries),
            'topCampaigns' => $topCampaigns,
        ]);
    }

    /**
     * @return array{0: Carbon, 1: Carbon}
     */
    protected function parseRange(?string $range): array
    {
        if ($range && str_contains($range, ' - ')) {
            [$from, $to] = explode(' - ', $range);

            return [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()];
        }

        return [Carbon::now()->subDays(30), Carbon::now()];
    }

    /**
     * @param  array{spend: float|int, impressions: float|int, clicks: float|int, conversions: float|int}  $totals
     * @return array<int, array<string, string>>
     */
    protected function formatKpis(array $totals): array
    {
        $kpiMap = [
            ['id' => 'spend', 'label' => 'Total Spend', 'format' => fn ($v) => '$' . number_format((float) $v, 2)],
            ['id' => 'impressions', 'label' => 'Impressions', 'format' => fn ($v) => number_format((int) $v)],
            ['id' => 'clicks', 'label' => 'Clicks', 'format' => fn ($v) => number_format((int) $v)],
            ['id' => 'conversions', 'label' => 'Conversions', 'format' => fn ($v) => number_format((int) $v)],
            ['id' => 'cpc', 'label' => 'Avg CPC', 'format' => fn () => $totals['clicks'] > 0 ? '$' . number_format($totals['spend'] / max($totals['clicks'], 1), 2) : '$0.00'],
            ['id' => 'cpm', 'label' => 'Avg CPM', 'format' => fn () => $totals['impressions'] > 0 ? '$' . number_format(($totals['spend'] / $totals['impressions']) * 1000, 2) : '$0.00'],
            ['id' => 'cpa', 'label' => 'Avg CPA', 'format' => fn () => $totals['conversions'] > 0 ? '$' . number_format($totals['spend'] / max($totals['conversions'], 1), 2) : '$0.00'],
        ];

        return array_map(function (array $kpi) use ($totals): array {
            $id = $kpi['id'];
            $format = $kpi['format'];

            return [
                'id' => $id,
                'label' => $kpi['label'],
                'value' => $format($totals[$id] ?? 0),
            ];
        }, $kpiMap);
    }

    /**
     * @param  Collection<int, object>  $timeseries
     * @return array<int, array<string, mixed>>
     */
    protected function formatSeries(Collection $timeseries): array
    {
        return [
            [
                'label' => 'Spend',
                'data' => $timeseries->pluck('spend'),
                'borderColor' => '#2563eb',
            ],
            [
                'label' => 'Impressions',
                'data' => $timeseries->pluck('impressions'),
                'borderColor' => '#22c55e',
            ],
            [
                'label' => 'Clicks',
                'data' => $timeseries->pluck('clicks'),
                'borderColor' => '#f59e0b',
            ],
        ];
    }
}
