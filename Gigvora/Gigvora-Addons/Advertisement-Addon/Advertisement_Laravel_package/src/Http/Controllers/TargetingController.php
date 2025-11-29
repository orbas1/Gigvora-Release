<?php

namespace Advertisement\Http\Controllers;

use Advertisement\Models\Campaign;
use Advertisement\Models\TargetingRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TargetingController
{
    public function store(Request $request, Campaign $campaign): JsonResponse
    {
        $data = $request->validate([
            'rules' => 'required|array',
            'rules.*.type' => 'required|string',
            'rules.*.value' => 'required',
            'rules.*.operator' => 'nullable|string'
        ]);

        $campaign->targetingRules()->delete();
        $created = collect($data['rules'])->map(fn ($rule) => $campaign->targetingRules()->create($rule));

        return response()->json($created);
    }
}
