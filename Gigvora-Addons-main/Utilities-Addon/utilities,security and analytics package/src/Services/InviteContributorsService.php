<?php

namespace ProNetwork\Services;

use Illuminate\Support\Collection;
use ProNetwork\Models\InviteContribution;

class InviteContributorsService
{
    public function invite(int $inviterId, int $postId, array $data = []): InviteContribution
    {
        return InviteContribution::create([
            'inviter_id' => $inviterId,
            'invitee_id' => $data['invitee_id'] ?? null,
            'post_id' => $postId,
            'role' => $data['role'] ?? null,
            'status' => $data['status'] ?? 'pending',
            'message' => $data['message'] ?? null,
        ]);
    }

    public function listForPost(int $postId): Collection
    {
        return InviteContribution::where('post_id', $postId)->get();
    }
}
