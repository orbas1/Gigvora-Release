<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller;
use ProNetwork\Services\ChatEnhancementService;
use ProNetwork\Services\ConnectionService;
use ProNetworkUtilitiesSecurityAnalytics\Http\Requests\ListConversationsRequest;
use ProNetworkUtilitiesSecurityAnalytics\Http\Requests\UpdateChatSettingsRequest;

class ChatEnhancementController extends Controller
{
    public function __construct(
        protected ChatEnhancementService $chatService,
        protected ConnectionService $connectionService
    ) {
    }

    public function listConversations(ListConversationsRequest $request)
    {
        $userId = $request->user()->id;
        $perPage = min(max((int) $request->validated('per_page', 15), 1), 100);
        $page = LengthAwarePaginator::resolveCurrentPage();
        $search = trim((string) $request->validated('search', ''));

        $connections = $this->connectionService->firstDegree($userId);

        if ($search !== '') {
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

        $data = collect($paginated->items())->map(function ($connection) {
            return [
                'conversation_id' => $connection->connection_id,
                'participant' => $connection->name ?? $connection->metadata['name'] ?? null,
                'last_message_at' => $connection->calculated_at,
                'mutual_count' => $connection->mutual_count ?? 0,
                'attachments_allowed' => $this->chatService->allowAttachments(),
            ];
        });

        return response()->json([
            'data' => $data,
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
            ],
        ]);
    }

    public function showConversation(Request $request, int $conversationId)
    {
        $userId = $request->user()->id;
        $connections = $this->connectionService->firstDegree($userId);
        $conversation = $connections->firstWhere('connection_id', $conversationId);

        $this->chatService->recordPresence($userId, 'active');

        return response()->json([
            'conversation' => $conversation,
            'messages' => $conversation?->messages ?? [],
            'attachments_allowed' => $this->chatService->allowAttachments(),
        ]);
    }

    public function deleteConversation(int $conversationId)
    {
        $this->chatService->deleteConversation($conversationId);

        return response()->json(['status' => 'deleted']);
    }

    public function clearConversation(int $conversationId)
    {
        $this->chatService->clearConversation($conversationId);

        return response()->json(['status' => 'cleared']);
    }

    public function updateSettings(UpdateChatSettingsRequest $request)
    {
        $settings = $request->validated() + ['attachments_allowed' => $this->chatService->allowAttachments()];

        return response()->json([
            'settings' => $settings,
        ]);
    }

    public function messageRequests(Request $request)
    {
        $userId = $request->user()->id;
        $requests = $this->connectionService->secondAndThirdDegree($userId)
            ->map(function ($connection) {
                return [
                    'request_id' => $connection->connection_id,
                    'from_user' => $connection->name ?? $connection->metadata['name'] ?? null,
                    'mutual_count' => $connection->mutual_count ?? 0,
                ];
            });

        return response()->json(['data' => $requests]);
    }

    public function acceptRequest(Request $request, int $requestId)
    {
        $mutual = $this->connectionService->mutualConnections($request->user()->id, $requestId);

        return response()->json([
            'accepted' => true,
            'mutual_count' => $mutual->mutual_count,
        ]);
    }

    public function declineRequest(int $requestId)
    {
        return response()->json([
            'declined' => true,
            'request_id' => $requestId,
        ]);
    }
}
