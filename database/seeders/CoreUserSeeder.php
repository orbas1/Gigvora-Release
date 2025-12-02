<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CoreUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['id' => 1],
            [
                'name' => 'Gigvora Admin',
                'email' => 'admin@gigvora.test',
                'email_verified_at' => now(),
                'password' => Hash::make('Password123!'),
                'user_role' => 'admin',
                'username' => 'gigvora-admin',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
