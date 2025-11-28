<?php

namespace ProNetwork\Services;

use Illuminate\Database\Eloquent\Model;
use ProNetwork\Models\Hashtag;
use ProNetwork\Support\Helpers\TagHelper;

class HashtagService
{
    public function parseAndStore(string $content): array
    {
        preg_match_all('/#(\w+)/u', $content, $matches);
        $tags = $matches[1] ?? [];

        return array_map([TagHelper::class, 'normalize'], $tags);
    }

    public function attachTo(Model $model, string $content): array
    {
        $tags = $this->parseAndStore($content);
        $search = app(SearchTagsDomain::class);
        $search->attach($model, $tags);

        return $tags;
    }

    public function find(string $tag): ?Hashtag
    {
        return Hashtag::where('normalized', TagHelper::normalize($tag))->first();
    }
}
