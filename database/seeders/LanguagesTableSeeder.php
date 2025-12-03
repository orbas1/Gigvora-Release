<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LanguagesTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $translations = [
            'Welcome back' => 'Welcome back',
            'Access your Gigvora workspace' => 'Access your Gigvora workspace',
            'Collaborate across feed, jobs, gigs, and live events without leaving one dashboard.' => 'Collaborate across feed, jobs, gigs, and live events without leaving one dashboard.',
            'Log in' => 'Log in',
            'Enter your credentials' => 'Enter your credentials',
            'Email' => 'Email',
            'Password' => 'Password',
            'Remember me' => 'Remember me',
            'Forgot password?' => 'Forgot password?',
            'Need an account?' => 'Need an account?',
            'Sign up' => 'Sign up',
        ];

        foreach ($translations as $phrase => $translation) {
            DB::table('languages')->updateOrInsert(
                ['name' => 'english', 'phrase' => $phrase],
                [
                    'translated' => $translation,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
