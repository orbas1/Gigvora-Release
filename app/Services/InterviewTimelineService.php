<?php

namespace App\Services;

use App\Models\UtilitiesCalendarEvent;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;

class InterviewTimelineService
{
    protected UtilitiesCalendarService $calendar;

    public function __construct(UtilitiesCalendarService $calendar)
    {
        $this->calendar = $calendar;
    }

    public function upcoming(User $user, int $limit = 5): Collection
    {
        return $this->calendar->interviewQuery($user->id)
            ->where('starts_at', '>=', now()->subDay())
            ->orderBy('starts_at')
            ->limit($limit)
            ->get()
            ->map(fn (UtilitiesCalendarEvent $event) => $this->mapEvent($event));
    }

    public function forCalendar(User $user, int $limit = 50): Collection
    {
        return $this->calendar->interviewQuery($user->id)
            ->orderBy('starts_at')
            ->limit($limit)
            ->get()
            ->map(fn (UtilitiesCalendarEvent $event) => $this->mapEvent($event, true));
    }

    protected function mapEvent(UtilitiesCalendarEvent $event, bool $rich = false): array
    {
        $date = $event->starts_at instanceof Carbon ? $event->starts_at : Carbon::parse($event->starts_at);
        $metadata = $event->metadata ?? [];

        $entry = [
            'id' => $event->id,
            'title' => $event->title,
            'subtitle' => $event->subtitle,
            'date' => $date,
            'status' => $event->status,
            'location' => $event->location ?? get_phrase('Online'),
            'cta_url' => $metadata['cta_url'] ?? null,
            'cta_label' => $metadata['cta_label'] ?? get_phrase('Open details'),
            'source' => $event->source,
            'meta' => $metadata,
        ];

        if ($rich) {
            $entry['source_label'] = match ($event->source) {
                'interactive_interview' => get_phrase('Interactive interview'),
                'jobs_application_status' => get_phrase('Application status'),
                default => get_phrase('Jobs interview'),
            };
        }

        return $entry;
    }
}

