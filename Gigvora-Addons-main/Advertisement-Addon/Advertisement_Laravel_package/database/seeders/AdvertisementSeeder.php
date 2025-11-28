<?php

namespace Database\Seeders;

use Advertisement\Models\KeywordPrice;
use Advertisement\Models\Placement;
use Illuminate\Database\Seeder;

class AdvertisementSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('advertisement.placements') as $placement) {
            Placement::firstOrCreate([
                'name' => $placement,
                'channel' => $placement,
            ], [
                'description' => ucfirst($placement) . ' placement imported from Sngine baseline.',
                'is_active' => true,
            ]);
        }

        $keywords = ['networking', 'jobs', 'freelance', 'podcast', 'webinar'];
        foreach ($keywords as $keyword) {
            KeywordPrice::firstOrCreate([
                'keyword' => $keyword,
            ], [
                'cpc' => rand(10, 40) / 10,
                'cpa' => rand(40, 100) / 10,
                'cpm' => rand(20, 60) / 10,
            ]);
        }
    }
}
