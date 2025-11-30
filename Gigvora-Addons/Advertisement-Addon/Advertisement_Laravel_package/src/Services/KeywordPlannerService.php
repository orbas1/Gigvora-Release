<?php

namespace Advertisement\Services;

use Illuminate\Support\Collection;

class KeywordPlannerService
{
    public function __construct(private readonly BidStrategyService $bidStrategy)
    {
    }

    public function priceKeywords(array $keywords): Collection
    {
        return collect($keywords)
            ->filter(fn ($keyword) => filled($keyword))
            ->map(fn ($keyword) => $this->bidStrategy->priceKeyword($keyword));
    }
}
