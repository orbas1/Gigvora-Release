<?php

namespace ProNetwork\Services;

use Illuminate\Support\Collection;
use ProNetwork\Models\Connection;
use ProNetwork\Models\MutualConnection;
use ProNetwork\Models\NetworkMetric;
use ProNetwork\Support\Enums\ConnectionDegree;

class ConnectionService
{
    public function cacheConnections(int $userId, iterable $firstDegreeIds): void
    {
        $firstDegreeIds = collect($firstDegreeIds)->unique()->values();
        $firstDegreeIds->each(function ($connectionId) use ($userId) {
            Connection::updateOrCreate([
                'user_id' => $userId,
                'connection_id' => $connectionId,
            ], [
                'degree' => ConnectionDegree::FIRST->value,
                'strength' => 1,
                'calculated_at' => now(),
            ]);
        });

        $this->refreshNetworkMetrics($userId);
    }

    public function firstDegree(int $userId): Collection
    {
        return Connection::query()->where('user_id', $userId)->where('degree', ConnectionDegree::FIRST->value)->get();
    }

    public function secondAndThirdDegree(int $userId): Collection
    {
        return Connection::query()
            ->where('user_id', $userId)
            ->whereIn('degree', [ConnectionDegree::SECOND->value, ConnectionDegree::THIRD->value])
            ->get();
    }

    public function mutualConnections(int $userId, int $targetUserId): MutualConnection
    {
        $first = $this->firstDegree($userId)->pluck('connection_id');
        $target = $this->firstDegree($targetUserId)->pluck('connection_id');
        $mutuals = $first->intersect($target)->values();

        return MutualConnection::updateOrCreate(
            ['user_id' => $userId, 'target_user_id' => $targetUserId],
            [
                'mutual_user_ids' => $mutuals,
                'mutual_count' => $mutuals->count(),
                'calculated_at' => now(),
            ]
        );
    }

    public function myNetworkSummary(int $userId): array
    {
        $first = $this->firstDegree($userId);
        $secondThird = $this->secondAndThirdDegree($userId);
        $metrics = $this->refreshNetworkMetrics($userId);

        return [
            'first_degree' => $first->count(),
            'second_degree' => $secondThird->where('degree', ConnectionDegree::SECOND->value)->count(),
            'third_degree' => $secondThird->where('degree', ConnectionDegree::THIRD->value)->count(),
            'mutual_connections' => $metrics?->mutual_count ?? 0,
            'suggestions' => $metrics?->suggestions ?? [],
        ];
    }

    public function refreshNetworkMetrics(int $userId): NetworkMetric
    {
        $first = $this->firstDegree($userId);
        $secondThird = $this->secondAndThirdDegree($userId);
        $suggestions = $secondThird
            ->sortByDesc('strength')
            ->take(10)
            ->map(fn ($connection) => $connection->connection_id)
            ->values();

        return NetworkMetric::updateOrCreate(
            ['user_id' => $userId],
            [
                'first_degree_count' => $first->count(),
                'second_degree_count' => $secondThird->where('degree', ConnectionDegree::SECOND->value)->count(),
                'third_degree_count' => $secondThird->where('degree', ConnectionDegree::THIRD->value)->count(),
                'mutual_count' => MutualConnection::where('user_id', $userId)->sum('mutual_count'),
                'suggestions' => $suggestions,
                'calculated_at' => now(),
            ]
        );
    }
}
