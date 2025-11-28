<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller;
use ProNetwork\Services\ConnectionService;
use ProNetwork\Support\Enums\ConnectionDegree;

class ConnectionsController extends Controller
{
    public function __construct(protected ConnectionService $connectionService)
    {
    }

    public function index(Request $request)
    {
        $summary = $this->connectionService->myNetworkSummary($request->user()->id);

        return view('pro_network::my_network.index', [
            'summary' => $summary,
        ]);
    }

    public function list(Request $request)
    {
        $userId = $request->user()->id;
        $degree = strtolower((string) $request->query('degree', ''));
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);
        $page = LengthAwarePaginator::resolveCurrentPage();

        $firstDegree = $this->connectionService->firstDegree($userId);
        $secondThird = $this->connectionService->secondAndThirdDegree($userId);

        $connections = match ($degree) {
            'first' => $firstDegree,
            'second' => $secondThird->where('degree', ConnectionDegree::SECOND->value),
            'third' => $secondThird->where('degree', ConnectionDegree::THIRD->value),
            default => $firstDegree->merge($secondThird),
        };

        if ($search = trim((string) $request->query('search', ''))) {
            $needle = mb_strtolower($search);
            $connections = $connections->filter(function ($connection) use ($needle) {
                $name = mb_strtolower((string) ($connection->name ?? $connection->metadata['name'] ?? ''));
                $idString = mb_strtolower((string) $connection->connection_id);

                return str_contains($name, $needle) || str_contains($idString, $needle);
            });
        }

        $paginated = new LengthAwarePaginator(
            $connections->forPage($page, $perPage)->values(),
            $connections->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        if ($request->expectsJson()) {
            return response()->json([
                'data' => $paginated->items(),
                'meta' => [
                    'current_page' => $paginated->currentPage(),
                    'per_page' => $paginated->perPage(),
                    'total' => $paginated->total(),
                ],
            ]);
        }

        return view('pro_network::my_network.connections', [
            'connections' => $paginated,
        ]);
    }

    public function mutual(Request $request, int $userId)
    {
        $mutual = $this->connectionService->mutualConnections($request->user()->id, $userId);

        if ($request->expectsJson()) {
            return response()->json([
                'mutual_count' => $mutual->mutual_count,
                'mutual_user_ids' => $mutual->mutual_user_ids,
            ]);
        }

        return view('pro_network::my_network.mutual', [
            'mutual' => $mutual,
        ]);
    }
}
