<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (! Schema::hasColumn('notifications', 'resource_type')) {
                $table->string('resource_type', 50)->nullable()->after('type')->index();
            }

            if (! Schema::hasColumn('notifications', 'resource_id')) {
                $table->string('resource_id', 100)->nullable()->after('resource_type');
            }

            if (! Schema::hasColumn('notifications', 'title')) {
                $table->string('title')->nullable()->after('view');
            }

            if (! Schema::hasColumn('notifications', 'message')) {
                $table->text('message')->nullable()->after('title');
            }

            if (! Schema::hasColumn('notifications', 'action_url')) {
                $table->string('action_url')->nullable()->after('message');
            }

            if (! Schema::hasColumn('notifications', 'data')) {
                $table->json('data')->nullable()->after('action_url');
            }
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (Schema::hasColumn('notifications', 'data')) {
                $table->dropColumn('data');
            }

            if (Schema::hasColumn('notifications', 'action_url')) {
                $table->dropColumn('action_url');
            }

            if (Schema::hasColumn('notifications', 'message')) {
                $table->dropColumn('message');
            }

            if (Schema::hasColumn('notifications', 'title')) {
                $table->dropColumn('title');
            }

            if (Schema::hasColumn('notifications', 'resource_id')) {
                $table->dropColumn('resource_id');
            }

            if (Schema::hasColumn('notifications', 'resource_type')) {
                $table->dropColumn('resource_type');
            }
        });
    }
};

