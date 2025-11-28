<?php

namespace Advertisement\Http\Controllers;

use Advertisement\Http\Requests\CreativeRequest;
use Advertisement\Models\Creative;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CreativeController
{
    public function index(Request $request): JsonResponse
    {
        $creatives = Creative::query()
            ->when($request->get('campaign_id'), fn ($q, $campaignId) => $q->where('campaign_id', $campaignId))
            ->when($request->get('ad_group_id'), fn ($q, $adGroupId) => $q->where('ad_group_id', $adGroupId))
            ->latest()
            ->paginate();

        return response()->json($creatives);
    }

    public function store(CreativeRequest $request): JsonResponse
    {
        $creative = Creative::create($request->validated());

        return response()->json($creative, 201);
    }

    public function update(CreativeRequest $request, Creative $creative): JsonResponse
    {
        $creative->update($request->validated());

        return response()->json($creative);
    }
}
