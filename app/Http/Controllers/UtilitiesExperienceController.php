<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Invite;
use App\Models\Notification;
use App\Models\Posts;
use App\Models\Saveforlater;
use App\Models\SavedProduct;
use App\Models\User;
use App\Models\UtilitiesCalendarEvent;
use App\Services\InterviewReminderService;
use App\Services\InterviewTimelineService;
use App\Services\UtilitiesQuickToolsService;
use ProNetwork\Services\AnalyticsService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

class UtilitiesExperienceController extends Controller
{
    public function __construct(
        protected UtilitiesQuickToolsService $quickTools,
        protected InterviewTimelineService $interviewTimeline,
        protected InterviewReminderService $interviewReminders,
        protected AnalyticsService $analytics
    ) {
    }

    public function notifications(): View
    {
        $this->ensureFeature('utilities_notifications_center');

        $user = auth()->user();
        $limit = (int) config('utilities.notifications.batch_limit', 50);
        $timezone = $user->timezone ?? config('app.timezone');

        $notifications = Notification::with(['getUserData', 'getEventData', 'getGroupData', 'getFundraiserData'])
            ->where('reciver_user_id', $user->id)
            ->orderByDesc('id')
            ->limit($limit * 2)
            ->get();

        $newNotifications = $notifications->where('status', '0')->values();
        $earlierNotifications = $notifications->where('status', '!=', '0')->values();

        $this->analytics->track(
            'utilities.notifications.viewed',
            [
                'total' => $notifications->count(),
                'unread' => $newNotifications->count(),
            ],
            $user,
            request()->ip()
        );

        return view('utilities.notifications', [
            'streams' => collect([
                [
                    'key' => 'new',
                    'label' => get_phrase('New notifications'),
                    'groups' => $this->groupNotificationsByDay($newNotifications, $timezone),
                ],
                [
                    'key' => 'recent',
                    'label' => get_phrase('Earlier this month'),
                    'groups' => $this->groupNotificationsByDay($earlierNotifications, $timezone),
                ],
            ]),
            'stats' => [
                'total' => $notifications->count(),
                'unread' => $newNotifications->count(),
                'week' => $notifications->where('created_at', '>=', now()->subDays(7))->count(),
            ],
            'filters' => $this->notificationFilters($notifications),
            'integrationLinks' => $this->quickTools->integrationLinks(),
            'timezone' => $timezone,
            'interviewReminders' => $this->interviewReminders->remindersFor($user),
            'interviewDigest' => $this->interviewReminders->digest($user),
        ]);
    }

    public function saved(): View
    {
        $this->ensureFeature('utilities_bookmarks');

        /** @var User $user */
        $user = auth()->user();
        $limit = (int) config('utilities.bookmarks.limit', 200);

        $savedPosts = $this->loadSavedPosts($user, $limit);
        $savedVideos = Saveforlater::with('getVideo')
            ->where('user_id', $user->id)
            ->whereNotNull('video_id')
            ->latest('id')
            ->limit($limit)
            ->get();
        $savedProducts = SavedProduct::with('productData')
            ->where('user_id', $user->id)
            ->latest('id')
            ->limit($limit)
            ->get();
        $savedJobs = $this->loadSavedJobs($user, $limit);
        $candidateNotes = $this->loadCandidateNotes($user);

        return view('utilities.saved', [
            'savedPosts' => $savedPosts,
            'savedVideos' => $savedVideos,
            'savedProducts' => $savedProducts,
            'savedJobs' => $savedJobs,
            'candidateNotes' => $candidateNotes,
            'summary' => [
                'posts' => $savedPosts->count(),
                'videos' => $savedVideos->count(),
                'marketplace' => $savedProducts->count(),
            ],
            'integrationLinks' => $this->quickTools->integrationLinks(),
        ]);
    }

    public function calendar(): View
    {
        $this->ensureFeature('utilities_calendar');

        /** @var User $user */
        $user = auth()->user();

        $hostedEvents = $this->upcomingEventsFor($user);
        $invitedEvents = $this->invitedEventsFor($user);
        $interviewEntries = $this->mapInterviewEntries($this->interviewTimeline->forCalendar($user));

        $timeline = $hostedEvents
            ->merge($invitedEvents)
            ->merge($interviewEntries)
            ->sortBy('date')
            ->values();

        return view('utilities.calendar', [
            'timeline' => $timeline,
            'insights' => [
                'hosted' => $hostedEvents->count(),
                'invited' => $invitedEvents->count(),
                'interviews' => $interviewEntries->count(),
                'pendingReminders' => Notification::where('reciver_user_id', $user->id)
                    ->where('status', '0')
                    ->count(),
            ],
            'integrationLinks' => $this->quickTools->integrationLinks(),
        ]);
    }

    public function acknowledgeHeaderAlert(Request $request): JsonResponse
    {
        $request->validate([
            'source' => 'required|string',
        ]);

        UtilitiesCalendarEvent::where('user_id', auth()->id())
            ->where('source', $request->string('source'))
            ->update(['status' => 'acknowledged']);

        return response()->json(['ok' => true]);
    }

    protected function ensureFeature(string $feature): void
    {
        abort_unless((bool) config("pro_network_utilities_security_analytics.features.$feature"), 404);
    }

    protected function notificationFilters(Collection $notifications): Collection
    {
        return $notifications
            ->groupBy('type')
            ->map(fn (Collection $items, string $type) => [
                'type' => $type,
                'label' => $this->notificationTypeLabel($type),
                'count' => $items->count(),
            ])
            ->sortByDesc('count')
            ->values();
    }

    protected function notificationTypeLabel(?string $type): string
    {
        return match ($type) {
            'event', 'event_invitation_accept' => get_phrase('Events'),
            'fundraiser', 'fundraiser_request_accept' => get_phrase('Fundraisers'),
            'group', 'group_invitation_accept' => get_phrase('Groups'),
            'profile', 'friend_request_accept' => get_phrase('Connections'),
            default => get_phrase('Alerts'),
        };
    }

    protected function loadSavedPosts(User $user, int $limit): Collection
    {
        $saved = json_decode($user->save_post ?? '[]', true) ?? [];

        if (! is_array($saved)) {
            $saved = [];
        }

        $postIds = array_slice(array_reverse($saved), 0, $limit);

        if (empty($postIds)) {
            return collect();
        }

        $posts = Posts::with('getUser')
            ->whereIn('post_id', $postIds)
            ->get()
            ->keyBy('post_id');

        return collect($postIds)
            ->map(fn ($postId) => $posts->get($postId))
            ->filter();
    }

    protected function loadSavedJobs(User $user, int $limit): Collection
    {
        if (! class_exists(\Jobs\Models\JobBookmark::class)) {
            return collect();
        }

        return \Jobs\Models\JobBookmark::with('job.company')
            ->where('user_id', $user->id)
            ->latest('id')
            ->limit($limit)
            ->get();
    }

    protected function loadCandidateNotes(User $user): Collection
    {
        if (! class_exists(\Jobs\Models\JobApplication::class)) {
            return collect();
        }

        return \Jobs\Models\JobApplication::with(['job.company', 'candidate.user'])
            ->whereNotNull('notes')
            ->whereHas('job.company', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest('updated_at')
            ->limit(10)
            ->get();
    }

    protected function upcomingEventsFor(User $user): Collection
    {
        return Event::where('user_id', $user->id)
            ->whereDate('event_date', '>=', Carbon::today()->toDateString())
            ->orderBy('event_date')
            ->limit(25)
            ->get()
            ->map(fn (Event $event) => $this->mapEvent($event, 'host'));
    }

    protected function invitedEventsFor(User $user): Collection
    {
        $eventIds = Invite::where('invite_reciver_id', $user->id)
            ->whereNotNull('event_id')
            ->pluck('event_id')
            ->unique()
            ->filter();

        if ($eventIds->isEmpty()) {
            return collect();
        }

        return Event::whereIn('id', $eventIds)
            ->whereDate('event_date', '>=', Carbon::today()->toDateString())
            ->orderBy('event_date')
            ->get()
            ->map(fn (Event $event) => $this->mapEvent($event, 'invite'));
    }

    protected function mapEvent(Event $event, string $source): array
    {
        $date = $this->parseDateTime($event->event_date ?? null, $event->event_time ?? null);

        return [
            'id' => $event->id,
            'title' => $event->title ?? get_phrase('Untitled event'),
            'location' => $event->location ?? get_phrase('Online'),
            'date' => $date,
            'source' => $source,
            'context_label' => $source === 'host'
                ? get_phrase('You are hosting')
                : get_phrase('Awaiting your response'),
            'status_label' => $source === 'host'
                ? get_phrase('Host')
                : get_phrase('Invite'),
            'link' => Route::has('single.event') ? route('single.event', $event->id) : url('/events/'.$event->id),
        ];
    }

    protected function parseDateTime(?string $date, ?string $time): Carbon
    {
        $candidate = trim(($date ?? '') . ' ' . ($time ?? ''));

        try {
            if ($candidate !== '') {
                return Carbon::parse($candidate);
            }

            if ($date) {
                return Carbon::parse($date);
            }
        } catch (\Exception $exception) {
            // Intentionally swallow and fall back to now()
        }

        return Carbon::now();
    }

    protected function mapInterviewEntries(Collection $entries): Collection
    {
        $fallback = $this->quickTools->integrationLinks()['utilitiesHub'] ?? url('/utilities/hub');

        return $entries->map(function (array $entry) use ($fallback) {
            return [
                'id' => $entry['id'],
                'title' => $entry['title'],
                'location' => $entry['location'] ?? get_phrase('Online'),
                'date' => $entry['date'],
                'source' => $entry['source'],
                'context_label' => $entry['source_label'] ?? get_phrase('Interview'),
                'status_label' => ucfirst($entry['status']),
                'link' => $entry['cta_url'] ?? $fallback,
            ];
        });
    }

    protected function groupNotificationsByDay(Collection $notifications, string $timezone): Collection
    {
        if ($notifications->isEmpty()) {
            return collect();
        }

        return $notifications
            ->groupBy(function (Notification $notification) use ($timezone) {
                $date = optional($notification->created_at)?->timezone($timezone);

                return $date?->startOfDay()->toDateString() ?? now($timezone)->startOfDay()->toDateString();
            })
            ->map(function (Collection $items, string $date) use ($timezone) {
                return [
                    'date' => $date,
                    'label' => $this->formatNotificationDateLabel($date, $timezone),
                    'items' => $items->values(),
                ];
            })
            ->sortByDesc('date')
            ->values();
    }

    protected function formatNotificationDateLabel(string $date, string $timezone): string
    {
        $target = \Carbon\Carbon::parse($date, $timezone);
        $today = now($timezone)->startOfDay();

        if ($target->equalTo($today)) {
            return get_phrase('Today');
        }

        if ($target->equalTo($today->copy()->subDay())) {
            return get_phrase('Yesterday');
        }

        return $target->format('M j');
    }
}


