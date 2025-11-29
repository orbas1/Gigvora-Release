<?php

namespace ProNetwork\Services;

use Illuminate\Support\Collection;
use ProNetwork\Models\MusicTrack;

class MusicLibraryService
{
    public function search(string $query, int $limit = 25): Collection
    {
        return MusicTrack::query()
            ->where('title', 'like', "%{$query}%")
            ->orWhere('artist', 'like', "%{$query}%")
            ->limit($limit)
            ->get();
    }

    public function list(int $limit = 50): Collection
    {
        return MusicTrack::query()->orderByDesc('created_at')->limit($limit)->get();
    }
}
