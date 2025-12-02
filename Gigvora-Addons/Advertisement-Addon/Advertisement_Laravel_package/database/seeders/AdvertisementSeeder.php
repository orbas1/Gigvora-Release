<?php

namespace Database\Seeders;

use Advertisement\Models\KeywordPrice;
use Advertisement\Models\Placement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdvertisementSeeder extends Seeder
{
    public function run(): void
    {
        $placements = config('advertisement.placements', ['feed', 'profile', 'search', 'jobs', 'gigs']);

        foreach ($placements as $key => $placement) {
            $slug = is_string($key) ? $key : (is_string($placement) ? $placement : Str::slug($placement['label'] ?? 'placement'));
            $label = is_array($placement) ? ($placement['label'] ?? Str::headline($slug)) : Str::headline($placement);
            $channel = is_array($placement) ? ($placement['channel'] ?? $slug) : $placement;
            $description = is_array($placement)
                ? ($placement['description'] ?? $label . ' placement')
                : ucfirst($placement) . ' placement imported from Sngine baseline.';

            Placement::updateOrCreate(
                ['name' => $slug],
                [
                    'channel' => $channel,
                    'description' => $description,
                    'is_active' => true,
                ]
            );
        }

        $keywords = [
            ['keyword' => 'networking', 'cpc' => 1.2, 'cpa' => 6.5, 'cpm' => 3.3],
            ['keyword' => 'jobs', 'cpc' => 1.0, 'cpa' => 7.5, 'cpm' => 3.9],
            ['keyword' => 'freelance', 'cpc' => 0.9, 'cpa' => 5.8, 'cpm' => 3.1],
            ['keyword' => 'podcast', 'cpc' => 0.8, 'cpa' => 5.4, 'cpm' => 2.9],
            ['keyword' => 'webinar', 'cpc' => 1.1, 'cpa' => 6.9, 'cpm' => 3.6],
        ];

        foreach ($keywords as $keyword) {
            KeywordPrice::firstOrCreate([
                'keyword' => $keyword['keyword'],
            ], [
                'cpc' => $keyword['cpc'],
                'cpa' => $keyword['cpa'],
                'cpm' => $keyword['cpm'],
            ]);
        }
    }
}
