<?php

namespace ProNetwork\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use ProNetwork\Support\Helpers\TagHelper;

class SearchUpgradeService
{
    public function applyTags(Builder $query, array $tags): Builder
    {
        $normalized = array_map([TagHelper::class, 'normalize'], $tags);

        return $query->where(function (Builder $sub) use ($normalized) {
            foreach ($normalized as $tag) {
                $sub->orWhereJsonContains('tags', $tag);
            }
        });
    }

    public function scopedSearch(Builder $query, string $term, array $columns = ['name', 'title', 'headline']): Builder
    {
        return $query->where(function (Builder $sub) use ($term, $columns) {
            foreach ($columns as $column) {
                $sub->orWhere($column, 'like', "%{$term}%");
            }
        });
    }
}
