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

        DB::table('users')->updateOrInsert(
            ['email' => 'buyer@gigvora.test'],
            [
                'name' => 'Gigvora Buyer',
                'email_verified_at' => now(),
                'password' => Hash::make('GigvoraBuyer!2025'),
                'user_role' => 'buyer',
                'username' => 'gigvora-buyer',
                'remember_token' => Str::random(10),
                'status' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('users')->updateOrInsert(
            ['email' => 'seller@gigvora.test'],
            [
                'name' => 'Gigvora Seller',
                'email_verified_at' => now(),
                'password' => Hash::make('GigvoraSeller!2025'),
                'user_role' => 'seller',
                'username' => 'gigvora-seller',
                'remember_token' => Str::random(10),
                'status' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
