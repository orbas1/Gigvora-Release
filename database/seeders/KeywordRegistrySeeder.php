<?php

namespace Database\Seeders;

use App\Services\KeywordRegistryService;
use Illuminate\Database\Seeder;

class KeywordRegistrySeeder extends Seeder
{
    public function run(): void
    {
        app(KeywordRegistryService::class)->seedFromExisting();
    }
}
