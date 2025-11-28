<?php

namespace ProNetwork\Services;

use Illuminate\Support\Collection;
use ProNetwork\Models\HashtagAssignment;
use ProNetwork\Models\NetworkMetric;
use ProNetwork\Support\DTOs\RecommendationResultDto;

class RecommendationService
{
    public function recommendByTags(string $type, int $limit = 10): RecommendationResultDto
    {
        $assignments = HashtagAssignment::query()
            ->where('hashtaggable_type', $type)
            ->with('hashtag')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();

        return new RecommendationResultDto($type, $assignments->pluck('hashtaggable'));
    }

    public function recommendConnections(int $userId, int $limit = 10): RecommendationResultDto
    {
        $metric = NetworkMetric::where('user_id', $userId)->first();
        $suggestions = collect($metric?->suggestions ?? [])->take($limit);

        return new RecommendationResultDto('connections', $suggestions->all());
    }

    public function trending(string $entityType, int $limit = 10): RecommendationResultDto
    {
        $assignments = HashtagAssignment::query()
            ->where('hashtaggable_type', $entityType)
            ->selectRaw('hashtaggable_id, hashtaggable_type, count(*) as uses')
            ->groupBy('hashtaggable_id', 'hashtaggable_type')
            ->orderByDesc('uses')
            ->limit($limit)
            ->get();

        return new RecommendationResultDto($entityType, $assignments->map->hashtaggable->filter()->values()->all());
    }
}
