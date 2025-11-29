<?php

namespace Advertisement\Http\Controllers;

use Advertisement\Models\Campaign;
use Advertisement\Models\Metric;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController
{
    public function index(Request $request, Campaign $campaign): JsonResponse
    {
        $from = $request->get('from') ? Carbon::parse($request->get('from')) : Carbon::now()->subDays(30);
        $to = $request->get('to') ? Carbon::parse($request->get('to')) : Carbon::now();

        $metrics = $campaign->metrics()
            ->whereBetween('recorded_at', [$from, $to])
            ->orderBy('recorded_at')
            ->get();

        $summary = [
            'impressions' => $metrics->sum('impressions'),
            'clicks' => $metrics->sum('clicks'),
            'conversions' => $metrics->sum('conversions'),
            'spend' => $metrics->sum('spend'),
        ];

        return response()->json([
            'summary' => $summary,
            'timeseries' => $metrics,
        ]);
    }

    public function store(Request $request, Campaign $campaign): JsonResponse
    {
        $data = $request->validate([
            'impressions' => 'required|integer|min:0',
            'clicks' => 'required|integer|min:0',
            'conversions' => 'required|integer|min:0',
            'spend' => 'required|numeric|min:0',
            'recorded_at' => 'required|date',
        ]);

        $metric = $campaign->metrics()->create($data);

        return response()->json($metric, 201);
    }
}
