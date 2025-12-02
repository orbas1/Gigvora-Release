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
        $this->call(CoreUserSeeder::class);

        $this->callAddonSeederIfExists(
            'Gigvora-Addons/Advertisement-Addon/Advertisement_Laravel_package/database/seeders/AdvertisementSeeder.php',
            \Database\Seeders\AdvertisementSeeder::class
        );

        $this->callAddonSeederIfExists(
            'Gigvora-Addons/Jobs-Addon/Jobs_Laravel_package/database/seeders/JobsDatabaseSeeder.php',
            \Jobs\Database\Seeders\JobsDatabaseSeeder::class
        );

        $this->callAddonSeederIfExists(
            'Gigvora-Addons/Interactive-Addon/Webinar_networking_interview_and_Podcast_Laravel_package/database/seeders/WebinarNetworkingInterviewPodcastSeeder.php',
            \Jobi\WebinarNetworkingInterviewPodcast\Database\Seeders\WebinarNetworkingInterviewPodcastSeeder::class
        );

        $this->callAddonSeederIfExists(
            'Gigvora-Addons/Ai-Headhunter-Launchpad-Addon/Ai-Headhunter-E_Launchpad-Laravel-Addon/database/seeders/TalentAiSeeder.php',
            \Database\Seeders\TalentAiSeeder::class
        );

        if (env('APP_FEATURE_DEMO_SEEDS', false)) {
            $this->call(DemoContentSeeder::class);
        }
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
