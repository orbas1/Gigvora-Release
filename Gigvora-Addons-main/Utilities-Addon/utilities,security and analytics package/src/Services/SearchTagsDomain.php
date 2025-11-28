<?php

namespace ProNetwork\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use ProNetwork\Models\Hashtag;
use ProNetwork\Models\HashtagAssignment;
use ProNetwork\Support\Helpers\TagHelper;

class SearchTagsDomain
{
    public function normalize(array|string $tags): array
    {
        return collect(is_array($tags) ? $tags : explode(',', (string) $tags))
            ->filter()
            ->map(fn ($tag) => TagHelper::normalize($tag))
            ->unique()
            ->values()
            ->all();
    }

    public function attach(Model $model, array|string $tags): Collection
    {
        $normalized = $this->normalize($tags);

        return collect($normalized)->map(function (string $tag) use ($model) {
            $hashtag = Hashtag::firstOrCreate(
                ['normalized' => $tag],
                ['tag' => $tag, 'usage_count' => 0]
            );

            $hashtag->increment('usage_count');

            return HashtagAssignment::updateOrCreate([
                'hashtag_id' => $hashtag->id,
                'hashtaggable_id' => $model->getKey(),
                'hashtaggable_type' => $model->getMorphClass(),
            ]);
        });
    }

    public function byTag(string $tag): Collection
    {
        $normalized = TagHelper::normalize($tag);
        $hashtag = Hashtag::where('normalized', $normalized)->first();

        return $hashtag?->assignments()->with('hashtaggable')->get() ?? collect();
    }
}
