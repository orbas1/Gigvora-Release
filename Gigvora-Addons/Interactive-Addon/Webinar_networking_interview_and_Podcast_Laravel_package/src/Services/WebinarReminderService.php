<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Services;

use App\Services\UtilitiesCalendarService;
use Illuminate\Support\Arr;
use Jobi\WebinarNetworkingInterviewPodcast\Models\Webinar;

class WebinarReminderService
{
    public function __construct(protected UtilitiesCalendarService $calendar)
    {
    }

    public function schedule(Webinar $webinar, int $userId): void
    {
        $offsets = $this->reminderOffsets($webinar);

        foreach ($offsets as $offset) {
            $startsAt = optional($webinar->starts_at)->copy()?->addMinutes($offset);
            if (! $startsAt || $startsAt->isPast()) {
                continue;
            }

            $this->calendar->upsert([
                'user_id' => $userId,
                'source' => 'interactive_webinar',
                'source_id' => (string) $webinar->id,
                'title' => $webinar->title,
                'subtitle' => __('Webinar reminder'),
                'description' => $webinar->description,
                'starts_at' => $startsAt,
                'ends_at' => $webinar->ends_at,
                'metadata' => [
                    'webinar_id' => $webinar->id,
                    'reminder_offset' => $offset,
                    'kind' => 'reminder',
                ],
            ]);
        }
    }

    public function cancel(Webinar $webinar, int $userId): void
    {
        $this->calendar->cancel($userId, 'interactive_webinar', (string) $webinar->id, 'cancelled');
    }

    protected function reminderOffsets(Webinar $webinar): array
    {
        $metadata = $webinar->metadata ?? [];
        $offsets = Arr::get($metadata, 'reminder_offsets', [-1440, -60]);

        return collect($offsets)
            ->map(fn ($offset) => (int) $offset)
            ->unique()
            ->values()
            ->all();
    }
}
