<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StandardUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'user@gigvora.test'],
            [
                'name' => 'Gigvora Member',
                'email_verified_at' => now(),
                'password' => Hash::make('GigvoraUser!2025'),
                'user_role' => 'general',
                'username' => 'gigvora-member',
                'remember_token' => Str::random(10),
                'status' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
