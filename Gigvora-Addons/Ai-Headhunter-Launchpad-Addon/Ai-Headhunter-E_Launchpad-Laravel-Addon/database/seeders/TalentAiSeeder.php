<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TalentAiSeeder extends Seeder
{
    public function run(): void
    {
        $plans = config('gigvora_talent_ai.ai.pricing_tiers', []);

        foreach ($plans as $slug => $plan) {
            DB::table('ai_subscription_plans')->updateOrInsert(
                ['slug' => $slug],
                [
                    'name' => $plan['name'] ?? Str::headline($slug),
                    'limits' => $plan['tokens'] ?? null,
                    'price' => $plan['price'] ?? 0,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
