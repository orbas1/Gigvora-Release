<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('keyword_prices', function (Blueprint $table) {
            $table->unsignedInteger('search_volume')->default(0)->after('keyword');
            $table->decimal('competition_score', 6, 4)->default(0)->after('search_volume');
            $table->decimal('quality_score', 6, 4)->default(0.5)->after('competition_score');
            $table->decimal('ctr', 6, 4)->default(0)->after('quality_score');
            $table->decimal('conversion_rate', 6, 4)->default(0)->after('ctr');
            $table->decimal('placement_multiplier', 6, 4)->default(1)->after('conversion_rate');
            $table->string('currency', 3)->default('USD')->after('placement_multiplier');
            $table->timestamp('last_synced_at')->nullable()->after('currency');
        });
    }

    public function down(): void
    {
        Schema::table('keyword_prices', function (Blueprint $table) {
            $table->dropColumn([
                'search_volume',
                'competition_score',
                'quality_score',
                'ctr',
                'conversion_rate',
                'placement_multiplier',
                'currency',
                'last_synced_at',
            ]);
        });
    }
};

