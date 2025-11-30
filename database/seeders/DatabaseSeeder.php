<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->callAddonSeederIfExists(
            'Gigvora-Addons/Advertisement-Addon/Advertisement_Laravel_package/database/seeders/AdvertisementSeeder.php',
            \Database\Seeders\AdvertisementSeeder::class
        );

        $this->callAddonSeederIfExists(
            'Gigvora-Addons/Ai-Headhunter-Launchpad-Addon/Ai-Headhunter-E_Launchpad-Laravel-Addon/database/seeders/TalentAiSeeder.php',
            \Database\Seeders\TalentAiSeeder::class
        );
    }

    protected function callAddonSeederIfExists(string $relativePath, string $class): void
    {
        $path = base_path($relativePath);

        if (! file_exists($path)) {
            return;
        }

        require_once $path;

        if (class_exists($class)) {
            $this->call($class);
        }
    }
}
