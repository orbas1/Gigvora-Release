<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ProNetwork\Models\AnalyticsEvent;
use ProNetwork\Models\AnalyticsMetric;
use ProNetwork\Services\AnalyticsService;
use ProNetworkUtilitiesSecurityAnalytics\Http\Requests\AnalyticsMetricsRequest;
use ProNetworkUtilitiesSecurityAnalytics\Http\Requests\AnalyticsSeriesRequest;

class AnalyticsController extends Controller
{
    public function __construct(protected AnalyticsService $analyticsService)
    {
    }

    public function overview()
    {
        return view('pro_network::analytics.overview');
    }

    public function metrics(AnalyticsMetricsRequest $request)
    {
        $validated = $request->validated();

        $query = AnalyticsMetric::query();

        if (! empty($validated['entity_type'] ?? null)) {
            $query->where('entity_type', $validated['entity_type']);
        }

        if (! empty($validated['entity_id'] ?? null)) {
            $query->where('entity_id', $validated['entity_id']);
        }

        if (! empty($validated['metrics'] ?? null)) {
            $query->whereIn('metric', $validated['metrics']);
        }

        $metrics = $query->get();

        $this->analyticsService->track('analytics.metrics.viewed', [
            'count' => $metrics->count(),
            'filters' => $validated,
        ], $request->user());

        return response()->json([
            'data' => $metrics,
        ]);
    }

    public function series(AnalyticsSeriesRequest $request)
    {
        $validated = $request->validated();

        $query = AnalyticsEvent::query();

        if (! empty($validated['events'] ?? null)) {
            $query->whereIn('event', $validated['events']);
        }

        if (! empty($validated['from'] ?? null)) {
            $query->whereDate('created_at', '>=', $validated['from']);
        }

        if (! empty($validated['to'] ?? null)) {
            $query->whereDate('created_at', '<=', $validated['to']);
        }

        $events = $query->orderBy('created_at')->get()->groupBy(
            fn (AnalyticsEvent $event) => $event->created_at->toDateString()
        )->map(fn ($group) => $group->count());

        $this->analyticsService->track('analytics.series.viewed', [
            'points' => $events->count(),
            'filters' => $validated,
        ], $request->user());

        return response()->json([
            'data' => $events,
        ]);
    }
}
