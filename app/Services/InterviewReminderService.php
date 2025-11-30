<?php

namespace App\Services;

use App\Models\User;
use App\Models\UtilitiesCalendarEvent;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class InterviewReminderService
{
    protected array $offsets = [
        ['label' => '24h', 'interval' => '1 day'],
        ['label' => '6h', 'interval' => '6 hours'],
        ['label' => '1h', 'interval' => '1 hour'],
    ];

    public function __construct(protected UtilitiesCalendarService $calendar)
    {
    }

    public function remindersFor(User $user, int $limit = 3): Collection
    {
        $events = $this->calendar->interviewQuery($user->id)
            ->where('starts_at', '>=', now()->subDay())
            ->orderBy('starts_at')
            ->limit($limit * 4)
            ->get();

        return $events
            ->map(fn (UtilitiesCalendarEvent $event) => $this->mapReminder($event))
            ->filter()
            ->sortBy(fn (array $reminder) => $reminder['remind_at'])
            ->values()
            ->take($limit);
    }

    public function digest(User $user, int $windowDays = 7): array
    {
        $events = $this->calendar->interviewQuery($user->id)
            ->where('starts_at', '>=', now()->subDays($windowDays))
            ->get();

        return [
            'scheduled' => $events->where('status', 'scheduled')->count(),
            'rescheduled' => $events->where('status', 'rescheduled')->count(),
            'completed' => $events->where('status', 'completed')->count(),
            'cancelled' => $events->where('status', 'cancelled')->count(),
        ];
    }

    protected function mapReminder(UtilitiesCalendarEvent $event): ?array
    {
        $start = $event->starts_at instanceof Carbon ? $event->starts_at : Carbon::parse($event->starts_at);

        foreach ($this->offsets as $offset) {
            $remindAt = $start->copy()->sub($this->parseInterval($offset['interval']));

            if ($remindAt->isFuture()) {
                return [
                    'event_id' => $event->id,
                    'title' => $event->title,
                    'status' => $event->status,
                    'source' => $event->source,
                    'lead_time' => $offset['label'],
                    'remind_at' => $remindAt,
                    'starts_at' => $start,
                    'cta_url' => $event->metadata['cta_url'] ?? null,
                ];
            }
        }

        return null;
    }

    protected function parseInterval(string $interval): \DateInterval
    {
        if ($interval === '1 day') {
            return new \DateInterval('P1D');
        }

        if ($interval === '6 hours') {
            return new \DateInterval('PT6H');
        }

        if ($interval === '1 hour') {
            return new \DateInterval('PT1H');
        }

        return new \DateInterval('PT0S');
    }
}


