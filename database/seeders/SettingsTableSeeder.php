<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $settings = [
            ['setting_id' => 1, 'type' => 'system_name', 'description' => 'Gigvora', 'created_at' => $now, 'updated_at' => $now],
            ['setting_id' => 2, 'type' => 'system_title', 'description' => 'Gigvora Social Platform', 'created_at' => $now, 'updated_at' => $now],
            ['setting_id' => 3, 'type' => 'system_email', 'description' => 'admin@gigvora.test', 'created_at' => $now, 'updated_at' => $now],
            ['setting_id' => 4, 'type' => 'system_phone', 'description' => '+1-555-0100', 'created_at' => $now, 'updated_at' => $now],
            ['setting_id' => 5, 'type' => 'system_address', 'description' => '123 Gigvora Ave, Remote', 'created_at' => $now, 'updated_at' => $now],
            ['setting_id' => 6, 'type' => 'system_footer', 'description' => 'Gigvora Inc.', 'created_at' => $now, 'updated_at' => $now],
            ['setting_id' => 7, 'type' => 'system_footer_link', 'description' => 'https://gigvora.local', 'created_at' => $now, 'updated_at' => $now],
            ['setting_id' => 8, 'type' => 'system_dark_logo', 'description' => 'gigvora-dark.svg', 'created_at' => $now, 'updated_at' => $now],
            ['setting_id' => 9, 'type' => 'system_light_logo', 'description' => 'gigvora-light.svg', 'created_at' => $now, 'updated_at' => $now],
            ['setting_id' => 10, 'type' => 'system_fav_icon', 'description' => 'gigvora-favicon.svg', 'created_at' => $now, 'updated_at' => $now],
            ['setting_id' => 11, 'type' => 'system_currency', 'description' => 'USD', 'created_at' => $now, 'updated_at' => $now],
            ['setting_id' => 12, 'type' => 'system_language', 'description' => 'english', 'created_at' => $now, 'updated_at' => $now],
            ['setting_id' => 13, 'type' => 'language', 'description' => 'english', 'created_at' => $now, 'updated_at' => $now],
            ['setting_id' => 14, 'type' => 'public_signup', 'description' => '1', 'created_at' => $now, 'updated_at' => $now],
            ['setting_id' => 15, 'type' => 'ad_charge_per_day', 'description' => '0.1', 'created_at' => $now, 'updated_at' => $now],
            ['setting_id' => 16, 'type' => 'version', 'description' => '1.0.0', 'created_at' => $now, 'updated_at' => $now],
            ['setting_id' => 17, 'type' => 'theme_color', 'description' => 'default', 'created_at' => $now, 'updated_at' => $now],
            ['setting_id' => 18, 'type' => 'about', 'description' => 'Gigvora is the unified SaaS hub for creators, recruiters, and brands.', 'created_at' => $now, 'updated_at' => $now],
            ['setting_id' => 19, 'type' => 'policy', 'description' => 'Privacy policy placeholder.', 'created_at' => $now, 'updated_at' => $now],
            ['setting_id' => 20, 'type' => 'term', 'description' => 'Terms of service placeholder.', 'created_at' => $now, 'updated_at' => $now],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['setting_id' => $setting['setting_id']],
                $setting
            );
        }
    }
}
