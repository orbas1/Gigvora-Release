<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller;
use ProNetwork\Models\ModerationQueue;
use ProNetwork\Models\SecurityEvent;
use ProNetwork\Services\ModerationService;
use ProNetwork\Services\SecurityEventService;
use ProNetwork\Support\Enums\SecurityEventType;
use ProNetworkUtilitiesSecurityAnalytics\Http\Requests\ModerateContentRequest;
use ProNetworkUtilitiesSecurityAnalytics\Http\Requests\ModerationQueueRequest;
use ProNetworkUtilitiesSecurityAnalytics\Http\Requests\SecurityEventsRequest;

class SecurityModerationController extends Controller
{
    public function __construct(
        protected SecurityEventService $securityEventService,
        protected ModerationService $moderationService
    ) {
    }

    public function securityLog(Request $request)
    {
        $events = SecurityEvent::latest()->limit(50)->get();

        $this->securityEventService->log(SecurityEventType::BLOCKED_ACTION, [
            'user_id' => $request->user()->id,
            'context' => ['action' => 'security_log_view'],
        ]);

        return view('pro_network::security.log', [
            'events' => $events,
        ]);
    }

    public function events(SecurityEventsRequest $request)
    {
        $validated = $request->validated();
        $query = SecurityEvent::query();

        if (! empty($validated['type'] ?? null)) {
            $query->whereIn('type', (array) $validated['type']);
        }

        if (! empty($validated['severity'] ?? null)) {
            $query->where('severity', $validated['severity']);
        }

        if (! empty($validated['from'] ?? null)) {
            $query->whereDate('created_at', '>=', $validated['from']);
        }

        if (! empty($validated['to'] ?? null)) {
            $query->whereDate('created_at', '<=', $validated['to']);
        }

        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = min(max((int) $validated['per_page'], 1), 100);

        $events = $query->orderByDesc('created_at')->forPage($page, $perPage)->get();

        return response()->json([
            'data' => $events,
            'meta' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $query->count(),
            ],
        ]);
    }

    public function moderationQueue(Request $request)
    {
        $queue = ModerationQueue::latest()->limit(50)->get();

        return view('pro_network::moderation.queue', [
            'queue' => $queue,
        ]);
    }

    public function queue(ModerationQueueRequest $request)
    {
        $validated = $request->validated();
        $query = ModerationQueue::query();

        if (! empty($validated['status'] ?? null)) {
            $query->where('status', $validated['status']);
        }

        if (! empty($validated['reason'] ?? null)) {
            $query->where('reason', 'like', '%'.$validated['reason'].'%');
        }

        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = min(max((int) $validated['per_page'], 1), 100);

        $queue = $query->orderByDesc('created_at')->forPage($page, $perPage)->get();

        return response()->json([
            'data' => $queue,
            'meta' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $query->count(),
            ],
        ]);
    }

    public function moderate(ModerateContentRequest $request)
    {
        $validated = $request->validated();
        $queueItem = ModerationQueue::findOrFail($validated['queue_id']);

        $notes = $validated['notes'] ?? null;
        if ($notes) {
            $filtered = $this->moderationService->applyBadWordRules($notes);
            $notes = $filtered['content'];
        }

        $queueItem->update([
            'status' => $validated['action'],
            'notes' => $notes,
            'actioned_by' => $request->user()->id,
            'resolved_at' => now(),
            'flags' => $validated['flags'] ?? $queueItem->flags,
        ]);

        return response()->json([
            'moderated' => true,
            'queue' => $queueItem->fresh(),
        ]);
    }
}
