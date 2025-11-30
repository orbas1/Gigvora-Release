<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (! Schema::hasColumn('users', 'profile_locked_for_verification')) {
                    $table->boolean('profile_locked_for_verification')
                        ->default(false)
                        ->after('status');
                }

                if (! Schema::hasColumn('users', 'profile_lock_reason')) {
                    $table->string('profile_lock_reason')
                        ->nullable()
                        ->after('profile_locked_for_verification');
                }
            });
        }

        if (Schema::hasTable('batchs')) {
            Schema::table('batchs', function (Blueprint $table) {
                if (! Schema::hasColumn('batchs', 'review_status')) {
                    $table->string('review_status')
                        ->default('approved')
                        ->after('status');
                }

                if (! Schema::hasColumn('batchs', 'eligibility_snapshot')) {
                    $table->json('eligibility_snapshot')
                        ->nullable()
                        ->after('description');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'profile_lock_reason')) {
                    $table->dropColumn('profile_lock_reason');
                }

                if (Schema::hasColumn('users', 'profile_locked_for_verification')) {
                    $table->dropColumn('profile_locked_for_verification');
                }
            });
        }

        if (Schema::hasTable('batchs')) {
            Schema::table('batchs', function (Blueprint $table) {
                if (Schema::hasColumn('batchs', 'eligibility_snapshot')) {
                    $table->dropColumn('eligibility_snapshot');
                }

                if (Schema::hasColumn('batchs', 'review_status')) {
                    $table->dropColumn('review_status');
                }
            });
        }
    }
};

