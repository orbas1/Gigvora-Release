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
        if (Schema::hasTable('live_streaming_engagements')) {
            return;
        }

        Schema::create('live_streaming_engagements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('live_streaming_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('type', 32);
            $table->decimal('amount', 12, 2)->default(0);
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->index(['live_streaming_id', 'type']);
            $table->foreign('live_streaming_id')
                ->references('streaming_id')
                ->on('live_streamings')
                ->cascadeOnDelete();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_streaming_engagements');
    }
};

