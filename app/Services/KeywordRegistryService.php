<?php

namespace App\Services;

use App\Models\KeywordRegistry;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class KeywordRegistryService
{
    public function registerKeyword(string $keyword, ?string $sourceType = null, ?int $sourceId = null, ?string $country = null): ?KeywordRegistry
    {
        $normalized = $this->normalize($keyword);

        if ($normalized === '') {
            return null;
        }

        return KeywordRegistry::query()->updateOrCreate(
            [
                'normalized' => $normalized,
                'source_type' => $sourceType,
                'source_id' => $sourceId,
            ],
            [
                'keyword' => $keyword,
                'country' => $country,
                'last_seen_at' => now(),
            ]
        );
    }

    public function registerFromHashtags(?string $rawHashtags, ?string $sourceType = null, ?int $sourceId = null, ?string $country = null): void
    {
        if (! $rawHashtags) {
            return;
        }

        preg_match_all('/#([\pL\pN_-]+)/u', $rawHashtags, $matches);
        foreach ($matches[1] ?? [] as $tag) {
            $this->incrementFrequency($this->registerKeyword($tag, $sourceType, $sourceId, $country));
        }
    }

    public function registerFromArray(array $keywords, ?string $sourceType = null, ?int $sourceId = null, ?string $country = null): void
    {
        foreach ($keywords as $keyword) {
            $this->incrementFrequency($this->registerKeyword($keyword, $sourceType, $sourceId, $country));
        }
    }

    public function relatedKeywords(array $keywords, int $limit = 15): Collection
    {
        $normalized = collect($keywords)->map(fn ($keyword) => $this->normalize($keyword))->filter();

        $matches = KeywordRegistry::query()
            ->when($normalized->isNotEmpty(), function ($query) use ($normalized) {
                $query->whereIn('normalized', $normalized);
            })
            ->orderByDesc('frequency')
            ->limit($limit)
            ->get();

        if ($matches->count() >= $limit) {
            return $matches;
        }

        $fallback = KeywordRegistry::query()
            ->orderByDesc('frequency')
            ->limit($limit - $matches->count())
            ->get();

        return $matches->concat($fallback)->unique('normalized')->take($limit);
    }

    public function seedFromExisting(): void
    {
        $this->seedFromPosts();
        $this->seedFromFreelance();
        $this->seedFromJobs();
        $this->seedFromInteractive();
        $this->seedFromAdvertisementKeywords();
    }

    protected function incrementFrequency(?KeywordRegistry $entry): void
    {
        if (! $entry) {
            return;
        }

        $entry->increment('frequency');
    }

    protected function seedFromPosts(): void
    {
        if (! Schema::hasTable('posts')) {
            return;
        }

        DB::table('posts')
            ->select(['post_id', 'hashtag', 'location'])
            ->whereNotNull('hashtag')
            ->orderByDesc('post_id')
            ->lazyById(500, 'post_id')
            ->each(function ($post) {
                $this->registerFromHashtags($post->hashtag, 'post', (int) $post->post_id, $post->location ?? null);
            });
    }

    protected function seedFromFreelance(): void
    {
        if (Schema::hasTable('project_categories')) {
            DB::table('project_categories')->select(['id', 'name'])->orderBy('id')->chunkById(200, function ($categories) {
                foreach ($categories as $category) {
                    $this->registerKeyword($category->name, 'project_category', (int) $category->id);
                }
            });
        }

        if (Schema::hasTable('gig_categories')) {
            DB::table('gig_categories')->select(['id', 'name'])->orderBy('id')->chunkById(200, function ($categories) {
                foreach ($categories as $category) {
                    $this->registerKeyword($category->name, 'gig_category', (int) $category->id);
                }
            });
        }

        if (Schema::hasTable('gig_tags')) {
            DB::table('gig_tags')->select(['id', 'tag_name'])->orderBy('id')->chunkById(200, function ($tags) {
                foreach ($tags as $tag) {
                    $this->registerKeyword($tag->tag_name, 'gig_tag', (int) $tag->id);
                }
            });
        }
    }

    protected function seedFromJobs(): void
    {
        if (! Schema::hasTable('job_categories')) {
            return;
        }

        DB::table('job_categories')->select(['id', 'name'])->orderBy('id')->chunkById(200, function ($categories) {
            foreach ($categories as $category) {
                $this->registerKeyword($category->name, 'job_category', (int) $category->id);
            }
        });
    }

    protected function seedFromInteractive(): void
    {
        if (Schema::hasTable('webinar_categories')) {
            DB::table('webinar_categories')->select(['id', 'name'])->orderBy('id')->chunkById(200, function ($categories) {
                foreach ($categories as $category) {
                    $this->registerKeyword($category->name, 'webinar_category', (int) $category->id);
                }
            });
        }

        if (Schema::hasTable('podcast_categories')) {
            DB::table('podcast_categories')->select(['id', 'name'])->orderBy('id')->chunkById(200, function ($categories) {
                foreach ($categories as $category) {
                    $this->registerKeyword($category->name, 'podcast_category', (int) $category->id);
                }
            });
        }
    }

    protected function seedFromAdvertisementKeywords(): void
    {
        if (! Schema::hasTable('keyword_prices')) {
            return;
        }

        DB::table('keyword_prices')->select(['id', 'keyword'])->orderBy('id')->chunkById(200, function ($keywords) {
            foreach ($keywords as $keyword) {
                $this->registerKeyword($keyword->keyword, 'ads_keyword_price', (int) $keyword->id);
            }
        });
    }

    protected function normalize(string $keyword): string
    {
        return Str::of($keyword)->lower()->squish()->trim()->value();
    }
}
