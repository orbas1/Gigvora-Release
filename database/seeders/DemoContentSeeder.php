<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        if (Schema::hasTable('audit_logs')) {
            DB::table('audit_logs')->insertOrIgnore([
                [
                    'actor_id' => null,
                    'target_type' => 'system',
                    'target_id' => 0,
                    'action' => 'demo_seed_initialized',
                    'changes' => json_encode(['note' => 'Demo seeds enabled for local review']),
                    'source' => 'seeder',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }
}
