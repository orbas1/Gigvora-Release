<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Services;

use App\Services\UtilitiesCalendarService;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Jobi\WebinarNetworkingInterviewPodcast\Models\NetworkingContactExchange;
use Jobi\WebinarNetworkingInterviewPodcast\Models\NetworkingParticipant;
use Jobi\WebinarNetworkingInterviewPodcast\Models\NetworkingSession;

class NetworkingContactService
{
    public function guardParticipant(NetworkingSession $session, int $userId, bool $allowWaitlist = false): ?NetworkingParticipant
    {
        $participant = NetworkingParticipant::where('networking_session_id', $session->id)
            ->where('user_id', $userId)
            ->first();

        if (! $participant) {
            return null;
        }

        if (! $allowWaitlist && $participant->status === 'waitlisted') {
            return null;
        }

        return $participant;
    }

    public function enforceRateLimit(NetworkingSession $session, int $userId, int $maxPerMinute = 8): void
    {
        $recentCount = NetworkingContactExchange::where('networking_session_id', $session->id)
            ->where('user_id', $userId)
            ->where('created_at', '>=', now()->subMinute())
            ->count();

        if ($recentCount >= $maxPerMinute) {
            throw ValidationException::withMessages([
                'contact' => __('Too many contact exchanges. Please wait before trying again.'),
            ]);
        }
    }

    public function exchange(
        NetworkingSession $session,
        int $userId,
        int $partnerId,
        ?string $notes = null,
        ?string $followUpAt = null,
        bool $starred = false,
        array $metadata = []
    ): NetworkingContactExchange {
        $payload = [
            'starred' => $starred,
            'notes' => $notes,
            'metadata' => $metadata,
        ];

        if ($followUpAt) {
            $payload['follow_up_at'] = Carbon::parse($followUpAt);
        }

        $exchange = NetworkingContactExchange::updateOrCreate(
            [
                'networking_session_id' => $session->id,
                'user_id' => $userId,
                'partner_id' => $partnerId,
            ],
            $payload
        );

        if ($followUpAt) {
            $this->scheduleFollowUp($session, $userId, $partnerId, $exchange->follow_up_at, Arr::get($metadata, 'partner_name'));
        }

        return $exchange->fresh();
    }

    protected function scheduleFollowUp(
        NetworkingSession $session,
        int $userId,
        int $partnerId,
        Carbon $followUpAt,
        ?string $partnerName = null
    ): void {
        /** @var UtilitiesCalendarService $calendar */
        $calendar = app(UtilitiesCalendarService::class);

        $calendar->upsert([
            'user_id' => $userId,
            'source' => 'interactive_networking',
            'source_id' => $session->id.'-'.$partnerId,
            'title' => __('Follow up with :name', ['name' => $partnerName ?: __('Networking contact')]),
            'subtitle' => $session->title,
            'description' => __('Networking recap and next steps for :session', ['session' => $session->title]),
            'starts_at' => $followUpAt,
            'metadata' => [
                'networking_session_id' => $session->id,
                'partner_id' => $partnerId,
            ],
        ]);
    }
}

