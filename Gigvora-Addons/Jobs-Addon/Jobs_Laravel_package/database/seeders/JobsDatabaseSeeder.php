<?php

namespace Jobs\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobsDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $pipelineId = DB::table('ats_pipelines')->updateOrInsert(
            ['company_id' => 0, 'name' => 'Default'],
            ['is_default' => true, 'updated_at' => now(), 'created_at' => now()]
        );

        $pipelineId = DB::table('ats_pipelines')
            ->where(['company_id' => 0, 'name' => 'Default'])
            ->value('id');

        $stages = [
            ['name' => 'Applied', 'position' => 1, 'color' => '#2f855a'],
            ['name' => 'Phone Screen', 'position' => 2, 'color' => '#3182ce'],
            ['name' => 'Interview', 'position' => 3, 'color' => '#805ad5'],
            ['name' => 'Offer', 'position' => 4, 'color' => '#dd6b20'],
            ['name' => 'Hired', 'position' => 5, 'color' => '#38a169'],
        ];

        foreach ($stages as $stage) {
            DB::table('ats_stages')->updateOrInsert(
                ['ats_pipeline_id' => $pipelineId, 'name' => $stage['name']],
                [
                    'position' => $stage['position'],
                    'color' => $stage['color'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        DB::table('jobs_categories')->insertOrIgnore([
            ['name' => 'Engineering', 'slug' => 'engineering'],
            ['name' => 'Design', 'slug' => 'design'],
            ['name' => 'Marketing', 'slug' => 'marketing'],
            ['name' => 'Sales', 'slug' => 'sales'],
        ]);
    }
}
