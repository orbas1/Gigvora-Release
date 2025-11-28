<?php

namespace Advertisement\Services;

use Advertisement\Models\KeywordPrice;
use Illuminate\Support\Collection;

class KeywordPlannerService
{
    public function priceKeywords(array $keywords): Collection
    {
        return collect($keywords)
            ->map(function ($keyword) {
                $record = KeywordPrice::firstOrCreate(
                    ['keyword' => $keyword],
                    [
                        'cpc' => rand(10, 50) / 10,
                        'cpa' => rand(50, 120) / 10,
                        'cpm' => rand(20, 80) / 10,
                    ]
                );

                return $record;
            });
    }
}
