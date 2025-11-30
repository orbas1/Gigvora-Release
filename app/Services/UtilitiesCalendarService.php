<?php

namespace App\Services;

use App\Models\UtilitiesCalendarEvent;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class UtilitiesCalendarService
{
    public function upsert(array $attributes): UtilitiesCalendarEvent
    {
        $payload = $this->preparePayload($attributes);

        return UtilitiesCalendarEvent::updateOrCreate(
            [
                'user_id' => $payload['user_id'],
                'source' => $payload['source'],
                'source_id' => $payload['source_id'],
            ],
            Arr::except($payload, ['user_id', 'source', 'source_id'])
        );
    }

    public function cancel(int $userId, string $source, string $sourceId, string $status = 'cancelled'): void
    {
        UtilitiesCalendarEvent::where([
            'user_id' => $userId,
            'source' => $source,
            'source_id' => (string) $sourceId,
        ])->update([
            'status' => $status,
        ]);
    }

    /**
     * Return query builder for interview events scoped to a user.
     */
    public function interviewQuery(int $userId)
    {
        return UtilitiesCalendarEvent::query()
            ->where('user_id', $userId)
            ->whereIn('source', ['jobs_interview', 'interactive_interview', 'jobs_application_status']);
    }

    protected function preparePayload(array $attributes): array
    {
        $startsAt = $this->castDate($attributes['starts_at'] ?? now());
        $endsAt = $attributes['ends_at'] ?? null;

        return [
            'user_id' => (int) $attributes['user_id'],
            'source' => (string) $attributes['source'],
            'source_id' => (string) $attributes['source_id'],
            'title' => $attributes['title'] ?? 'Interview',
            'subtitle' => $attributes['subtitle'] ?? null,
            'description' => $attributes['description'] ?? null,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt ? $this->castDate($endsAt) : null,
            'location' => $attributes['location'] ?? null,
            'status' => $attributes['status'] ?? 'scheduled',
            'metadata' => $attributes['metadata'] ?? [],
        ];
    }

    protected function castDate(Carbon|string $value): Carbon
    {
        return $value instanceof Carbon ? $value : Carbon::parse($value);
    }
}

