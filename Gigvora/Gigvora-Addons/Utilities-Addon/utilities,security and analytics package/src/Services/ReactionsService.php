<?php

namespace ProNetwork\Services;

use Illuminate\Database\Eloquent\Model;
use ProNetwork\Models\ProfileReactionScore;
use ProNetwork\Models\Reaction;
use ProNetwork\Models\ReactionAggregate;
use ProNetwork\Support\Enums\ReactionType;

class ReactionsService
{
    public function react(Model $reactable, int $userId, ReactionType $type): Reaction
    {
        $existing = Reaction::where([
            'reactable_id' => $reactable->getKey(),
            'reactable_type' => $reactable->getMorphClass(),
            'user_id' => $userId,
        ])->first();

        $reaction = Reaction::updateOrCreate([
            'reactable_id' => $reactable->getKey(),
            'reactable_type' => $reactable->getMorphClass(),
            'user_id' => $userId,
        ], [
            'type' => $type,
            'weight' => $type === ReactionType::DISLIKE ? -1 : 1,
        ]);

        $this->aggregate($reactable);

        if ($ownerId = $this->detectOwnerId($reactable)) {
            if ($existing && $existing->type !== $type) {
                $previousType = $existing->type instanceof ReactionType ? $existing->type : ReactionType::from($existing->type);
                $this->adjustProfileScore($ownerId, $previousType, -1);
            }

            $this->adjustProfileScore($ownerId, $type, 1);
        }

        return $reaction;
    }

    public function remove(Model $reactable, int $userId): void
    {
        $reaction = Reaction::where([
            'reactable_id' => $reactable->getKey(),
            'reactable_type' => $reactable->getMorphClass(),
            'user_id' => $userId,
        ])->first();

        if ($reaction) {
            $reaction->delete();
        }

        $this->aggregate($reactable);

        if ($reaction && ($ownerId = $this->detectOwnerId($reactable))) {
            $reactionType = $reaction->type instanceof ReactionType ? $reaction->type : ReactionType::from($reaction->type);
            $this->adjustProfileScore($ownerId, $reactionType, -1);
        }
    }

    public function aggregate(Model $reactable): void
    {
        $reactions = Reaction::where([
            'reactable_id' => $reactable->getKey(),
            'reactable_type' => $reactable->getMorphClass(),
        ])->get();

        $counts = $reactions->groupBy('type')->map->count();
        $dislikes = $counts[ReactionType::DISLIKE->value] ?? 0;

        ReactionAggregate::updateOrCreate([
            'reactable_id' => $reactable->getKey(),
            'reactable_type' => $reactable->getMorphClass(),
        ], [
            'counts' => $counts,
            'dislikes' => $dislikes,
        ]);
    }

    protected function adjustProfileScore(?int $userId, ReactionType $type, int $direction): void
    {
        if (! $userId) {
            return;
        }

        $score = ProfileReactionScore::firstOrCreate(['user_id' => $userId]);
        $breakdown = collect($score->reaction_breakdown ?? []);
        $breakdown[$type->value] = max(0, ($breakdown[$type->value] ?? 0) + $direction);

        $score->update([
            'like_score' => max(0, $score->like_score + ($type === ReactionType::DISLIKE ? 0 : $direction)),
            'dislike_count' => max(0, $score->dislike_count + ($type === ReactionType::DISLIKE ? $direction : 0)),
            'reaction_breakdown' => $breakdown,
        ]);
    }

    protected function detectOwnerId(Model $reactable): ?int
    {
        if (method_exists($reactable, 'getAttribute')) {
            $owner = $reactable->getAttribute('user_id');
            if ($owner) {
                return (int) $owner;
            }
        }

        return property_exists($reactable, 'user_id') ? (int) ($reactable->user_id ?? 0) : null;
    }
}
