<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Jobi\WebinarNetworkingInterviewPodcast\Models\NetworkingPairing;
use Jobi\WebinarNetworkingInterviewPodcast\Models\NetworkingParticipant;
use Jobi\WebinarNetworkingInterviewPodcast\Models\NetworkingSession;

class NetworkingPairingService
{
    public function generateRound(NetworkingSession $session, int $round): Collection
    {
        $participants = $session->confirmedParticipants()
            ->with('user')
            ->orderBy('rotation_position')
            ->get();

        $existingPairs = $this->existingPairKeys($session);
        $assignments = collect();

        if (($session->metadata['template'] ?? 'speed') === 'group') {
            $assignments = $this->buildGroupAssignments($session, $participants, $round, $existingPairs);
        } else {
            $assignments = $this->buildSpeedAssignments($participants, $round, $existingPairs);
        }

        $session->update(['status' => 'in_rotation']);

        return $assignments;
    }

    public function nextRoundNumber(NetworkingSession $session): int
    {
        $current = NetworkingPairing::where('networking_session_id', $session->id)->max('round');

        return $current ? $current + 1 : 1;
    }

    protected function buildSpeedAssignments(Collection $participants, int $round, Collection $existingPairs): Collection
    {
        $pool = $participants->shuffle();
        $assignments = collect();

        while ($pool->count() > 1) {
            $participant = $pool->shift();
            $partnerIndex = $pool->search(function (NetworkingParticipant $candidate) use ($participant, $existingPairs) {
                return ! $existingPairs->contains($this->pairKey($participant->id, $candidate->id));
            });

            if ($partnerIndex === false) {
                $partnerIndex = 0;
            }

            $partner = $pool->splice((int) $partnerIndex, 1)->first();

            if ($partner) {
                $assignments->push($this->storePairing($participant, $partner, $round));
                $assignments->push($this->storePairing($partner, $participant, $round));
            }
        }

        if ($pool->count() === 1) {
            $last = $pool->first();
            $assignments->push($this->storePairing($last, null, $round, ['status' => 'waiting']));
        }

        return $assignments;
    }

    protected function buildGroupAssignments(
        NetworkingSession $session,
        Collection $participants,
        int $round,
        Collection $existingPairs
    ): Collection {
        $max = (int) ($session->metadata['max_per_round'] ?? 4);
        $groups = $participants->shuffle()->chunk(max(2, $max));
        $assignments = collect();

        foreach ($groups as $index => $group) {
            $groupKey = sprintf('round-%d-group-%d', $round, $index + 1);
            foreach ($group as $participant) {
                $partnerId = $group->firstWhere('user_id', '!=', $participant->user_id)?->user_id;
                $assignments->push($this->storePairing($participant, $partnerId ? $group->firstWhere('user_id', $partnerId) : null, $round, [
                    'group_key' => $groupKey,
                    'avoid_repeat' => $partnerId ? ! $existingPairs->contains($this->pairKey($participant->id, $group->firstWhere('user_id', $partnerId)?->id)) : true,
                ], $groupKey));
            }
        }

        return $assignments;
    }

    protected function storePairing(
        NetworkingParticipant $participant,
        ?NetworkingParticipant $partner,
        int $round,
        array $metadata = [],
        ?string $groupKey = null
    ): NetworkingPairing {
        return NetworkingPairing::updateOrCreate(
            [
                'networking_session_id' => $participant->networking_session_id,
                'participant_id' => $participant->id,
                'round' => $round,
            ],
            [
                'partner_id' => $partner?->id,
                'group_key' => $groupKey,
                'metadata' => $metadata,
            ]
        );
    }

    protected function existingPairKeys(NetworkingSession $session): Collection
    {
        return NetworkingPairing::where('networking_session_id', $session->id)
            ->get()
            ->map(fn ($pair) => $this->pairKey($pair->participant_id, $pair->partner_id));
    }

    protected function pairKey(?int $a, ?int $b): string
    {
        $sorted = collect([$a, $b])->filter()->sort()->values();

        return $sorted->isEmpty() ? Str::uuid()->toString() : $sorted->implode('-');
    }
}
