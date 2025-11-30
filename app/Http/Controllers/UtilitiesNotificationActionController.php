<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use ProNetwork\Services\AnalyticsService;

class UtilitiesNotificationActionController extends Controller
{
    public function __construct(protected AnalyticsService $analytics)
    {
    }

    public function markAsRead(Request $request, Notification $notification): JsonResponse
    {
        $user = $request->user();

        abort_unless($notification->reciver_user_id === $user->id, 403);

        $notification->update([
            'status' => '1',
            'view' => '1',
        ]);

        $unread = Notification::where('reciver_user_id', $user->id)
            ->where('status', '0')
            ->count();

        $this->analytics->track(
            'utilities.notification.read',
            [
                'notification_type' => $notification->type,
                'resource_type' => $notification->resource_type,
                'resource_id' => $notification->resource_id,
            ],
            $user,
            $request->ip()
        );

        return response()->json([
            'status' => 'read',
            'id' => $notification->id,
            'type' => $notification->type,
            'unread' => $unread,
        ]);
    }
}

