<?php

namespace Jobs\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobsDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ats_pipelines')->insert([
            ['company_id' => 0, 'name' => 'Default', 'is_default' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('ats_stages')->insert([
            ['ats_pipeline_id' => 1, 'name' => 'Applied', 'position' => 1, 'color' => '#2f855a', 'created_at' => now(), 'updated_at' => now()],
            ['ats_pipeline_id' => 1, 'name' => 'Phone Screen', 'position' => 2, 'color' => '#3182ce', 'created_at' => now(), 'updated_at' => now()],
            ['ats_pipeline_id' => 1, 'name' => 'Interview', 'position' => 3, 'color' => '#805ad5', 'created_at' => now(), 'updated_at' => now()],
            ['ats_pipeline_id' => 1, 'name' => 'Offer', 'position' => 4, 'color' => '#dd6b20', 'created_at' => now(), 'updated_at' => now()],
            ['ats_pipeline_id' => 1, 'name' => 'Hired', 'position' => 5, 'color' => '#38a169', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('jobs_categories')->insertOrIgnore([
            ['name' => 'Engineering', 'slug' => 'engineering'],
            ['name' => 'Design', 'slug' => 'design'],
            ['name' => 'Marketing', 'slug' => 'marketing'],
            ['name' => 'Sales', 'slug' => 'sales'],
        ]);
    }
}
