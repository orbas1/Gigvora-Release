<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('forecasts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->cascadeOnDelete();
            $table->unsignedBigInteger('reach')->default(0);
            $table->unsignedBigInteger('clicks')->default(0);
            $table->unsignedBigInteger('conversions')->default(0);
            $table->decimal('estimated_spend', 12, 2)->default(0);
            $table->json('assumptions')->nullable();
            $table->timestamps();
            $table->index(['campaign_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forecasts');
    }
};
