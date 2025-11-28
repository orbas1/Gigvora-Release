<?php

namespace Advertisement\Http\Controllers;

use Advertisement\Http\Requests\CampaignRequest;
use Advertisement\Models\Campaign;
use Advertisement\Policies\CampaignPolicy;
use Advertisement\Services\ForecastingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class CampaignController
{
    public function index(): JsonResponse
    {
        return response()->json(
            Campaign::with(['advertiser', 'creatives', 'metrics', 'targetingRules', 'forecasts'])
                ->latest()
                ->paginate()
        );
    }

    public function show(Campaign $campaign): JsonResponse
    {
        return response()->json($campaign->load(['advertiser', 'creatives', 'metrics', 'targetingRules', 'forecasts']));
    }

    public function store(CampaignRequest $request): JsonResponse
    {
        $campaign = Campaign::create($request->validated());

        return response()->json($campaign->load('advertiser'), 201);
    }

    public function update(CampaignRequest $request, Campaign $campaign): JsonResponse
    {
        Gate::authorize('update', $campaign);

        $campaign->update($request->validated());

        return response()->json($campaign);
    }

    public function forecast(Campaign $campaign, ForecastingService $service): JsonResponse
    {
        Gate::authorize('view', $campaign);
        $forecast = $service->run($campaign);

        return response()->json($forecast);
    }
}
