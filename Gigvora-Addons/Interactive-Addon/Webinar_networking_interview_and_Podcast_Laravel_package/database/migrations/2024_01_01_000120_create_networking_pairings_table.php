<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('networking_pairings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('networking_session_id');
            $table->unsignedBigInteger('participant_id');
            $table->unsignedBigInteger('partner_id')->nullable();
            $table->unsignedInteger('round');
            $table->string('group_key')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique([
                'networking_session_id',
                'participant_id',
                'round',
            ], 'networking_pairings_participant_round_unique');
            $table->index(['networking_session_id', 'round']);
            $table->index(['networking_session_id', 'partner_id']);
            $table->foreign('networking_session_id')
                ->references('id')
                ->on('networking_sessions')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('networking_pairings');
    }
};
