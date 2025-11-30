<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (!Schema::hasColumn('posts', 'composer_mode')) {
                $table->string('composer_mode', 32)->default('standard')->index();
            }

            if (!Schema::hasColumn('posts', 'studio_manifest')) {
                $table->json('studio_manifest')->nullable();
            }

            if (!Schema::hasColumn('posts', 'scheduled_for')) {
                $table->timestamp('scheduled_for')->nullable()->index();
            }

            if (!Schema::hasColumn('posts', 'live_config')) {
                $table->json('live_config')->nullable();
            }
        });

        Schema::table('media_files', function (Blueprint $table) {
            if (!Schema::hasColumn('media_files', 'resolution_preset')) {
                $table->string('resolution_preset', 16)->default('auto')->after('is_reel');
            }

            if (!Schema::hasColumn('media_files', 'processing_manifest')) {
                $table->json('processing_manifest')->nullable()->after('resolution_preset');
            }
        });

        Schema::table('stories', function (Blueprint $table) {
            if (!Schema::hasColumn('stories', 'resolution_preset')) {
                $table->string('resolution_preset', 16)->default('auto')->after('status');
            }

            if (!Schema::hasColumn('stories', 'studio_manifest')) {
                $table->json('studio_manifest')->nullable()->after('resolution_preset');
            }
        });

        Schema::table('live_streamings', function (Blueprint $table) {
            if (!Schema::hasColumn('live_streamings', 'engagement_config')) {
                $table->json('engagement_config')->nullable();
            }

            if (!Schema::hasColumn('live_streamings', 'viewer_goal')) {
                $table->unsignedInteger('viewer_goal')->default(0);
            }

            if (!Schema::hasColumn('live_streamings', 'viewer_peak')) {
                $table->unsignedInteger('viewer_peak')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasColumn('posts', 'composer_mode')) {
                $table->dropColumn('composer_mode');
            }
            if (Schema::hasColumn('posts', 'studio_manifest')) {
                $table->dropColumn('studio_manifest');
            }
            if (Schema::hasColumn('posts', 'scheduled_for')) {
                $table->dropColumn('scheduled_for');
            }
            if (Schema::hasColumn('posts', 'live_config')) {
                $table->dropColumn('live_config');
            }
        });

        Schema::table('media_files', function (Blueprint $table) {
            if (Schema::hasColumn('media_files', 'resolution_preset')) {
                $table->dropColumn('resolution_preset');
            }
            if (Schema::hasColumn('media_files', 'processing_manifest')) {
                $table->dropColumn('processing_manifest');
            }
        });

        Schema::table('stories', function (Blueprint $table) {
            if (Schema::hasColumn('stories', 'resolution_preset')) {
                $table->dropColumn('resolution_preset');
            }
            if (Schema::hasColumn('stories', 'studio_manifest')) {
                $table->dropColumn('studio_manifest');
            }
        });

        Schema::table('live_streamings', function (Blueprint $table) {
            if (Schema::hasColumn('live_streamings', 'engagement_config')) {
                $table->dropColumn('engagement_config');
            }
            if (Schema::hasColumn('live_streamings', 'viewer_goal')) {
                $table->dropColumn('viewer_goal');
            }
            if (Schema::hasColumn('live_streamings', 'viewer_peak')) {
                $table->dropColumn('viewer_peak');
            }
        });
    }
};

