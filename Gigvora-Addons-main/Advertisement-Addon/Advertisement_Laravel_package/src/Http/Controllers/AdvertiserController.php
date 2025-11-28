<?php

namespace Advertisement\Http\Controllers;

use Advertisement\Http\Requests\AdvertiserRequest;
use Advertisement\Models\Advertiser;
use Illuminate\Http\JsonResponse;

class AdvertiserController
{
    public function index(): JsonResponse
    {
        return response()->json(Advertiser::paginate());
    }

    public function store(AdvertiserRequest $request): JsonResponse
    {
        $advertiser = Advertiser::create($request->validated());

        return response()->json($advertiser, 201);
    }

    public function update(AdvertiserRequest $request, Advertiser $advertiser): JsonResponse
    {
        $advertiser->update($request->validated());

        return response()->json($advertiser);
    }
}
