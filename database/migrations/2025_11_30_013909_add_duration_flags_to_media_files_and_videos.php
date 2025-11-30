<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('media_files', function (Blueprint $table) {
            if (!Schema::hasColumn('media_files', 'duration_seconds')) {
                $table->unsignedInteger('duration_seconds')->nullable()->after('file_type');
            }

            if (!Schema::hasColumn('media_files', 'is_reel')) {
                $table->boolean('is_reel')->default(false)->after('duration_seconds');
            }
        });

        Schema::table('videos', function (Blueprint $table) {
            if (!Schema::hasColumn('videos', 'duration_seconds')) {
                $table->unsignedInteger('duration_seconds')->nullable()->after('category');
            }

            if (!Schema::hasColumn('videos', 'is_reel')) {
                $table->boolean('is_reel')->default(false)->after('duration_seconds');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('media_files', function (Blueprint $table) {
            if (Schema::hasColumn('media_files', 'is_reel')) {
                $table->dropColumn('is_reel');
            }

            if (Schema::hasColumn('media_files', 'duration_seconds')) {
                $table->dropColumn('duration_seconds');
            }
        });

        Schema::table('videos', function (Blueprint $table) {
            if (Schema::hasColumn('videos', 'is_reel')) {
                $table->dropColumn('is_reel');
            }

            if (Schema::hasColumn('videos', 'duration_seconds')) {
                $table->dropColumn('duration_seconds');
            }
        });
    }
};
