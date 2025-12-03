<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'nickname')) {
                $table->string('nickname')->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'friends')) {
                $table->longText('friends')->nullable()->after('nickname');
            }
            if (!Schema::hasColumn('users', 'followers')) {
                $table->longText('followers')->nullable()->after('friends');
            }
            if (!Schema::hasColumn('users', 'gender')) {
                $table->string('gender', 100)->nullable()->after('followers');
            }
            if (!Schema::hasColumn('users', 'studied_at')) {
                $table->string('studied_at', 300)->nullable()->after('gender');
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->string('address', 300)->nullable()->after('studied_at');
            }
            if (!Schema::hasColumn('users', 'profession')) {
                $table->string('profession', 300)->nullable()->after('address');
            }
            if (!Schema::hasColumn('users', 'job')) {
                $table->string('job', 300)->nullable()->after('profession');
            }
            if (!Schema::hasColumn('users', 'marital_status')) {
                $table->string('marital_status')->nullable()->after('job');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 100)->nullable()->after('marital_status');
            }
            if (!Schema::hasColumn('users', 'date_of_birth')) {
                $table->string('date_of_birth', 100)->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'about')) {
                $table->longText('about')->nullable()->after('date_of_birth');
            }
            if (!Schema::hasColumn('users', 'save_post')) {
                $table->longText('save_post')->nullable()->after('about');
            }
            if (!Schema::hasColumn('users', 'photo')) {
                $table->string('photo')->nullable()->after('save_post');
            }
            if (!Schema::hasColumn('users', 'cover_photo')) {
                $table->string('cover_photo')->nullable()->after('photo');
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status', 100)->nullable()->after('cover_photo');
            }
            if (!Schema::hasColumn('users', 'lastActive')) {
                $table->timestamp('lastActive')->nullable()->after('status');
            }
            if (!Schema::hasColumn('users', 'timezone')) {
                $table->string('timezone')->nullable()->after('lastActive');
            }
            if (!Schema::hasColumn('users', 'payment_settings')) {
                $table->longText('payment_settings')->nullable()->after('timezone');
            }
            if (!Schema::hasColumn('users', 'profile_status')) {
                $table->string('profile_status')->nullable()->after('payment_settings');
            }
            if (!Schema::hasColumn('users', 'moderation_strikes')) {
                $table->unsignedInteger('moderation_strikes')->default(0)->after('profile_status');
            }
            if (!Schema::hasColumn('users', 'shadow_banned_until')) {
                $table->timestamp('shadow_banned_until')->nullable()->after('moderation_strikes');
            }
            if (!Schema::hasColumn('users', 'banned_reason')) {
                $table->string('banned_reason')->nullable()->after('shadow_banned_until');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach ([
                'nickname',
                'friends',
                'followers',
                'gender',
                'studied_at',
                'address',
                'profession',
                'job',
                'marital_status',
                'phone',
                'date_of_birth',
                'about',
                'save_post',
                'photo',
                'cover_photo',
                'status',
                'lastActive',
                'timezone',
                'payment_settings',
                'profile_status',
                'moderation_strikes',
                'shadow_banned_until',
                'banned_reason',
            ] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
