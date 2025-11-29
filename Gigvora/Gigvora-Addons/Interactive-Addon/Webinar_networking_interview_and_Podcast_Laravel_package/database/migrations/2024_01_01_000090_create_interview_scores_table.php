<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('interview_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interview_id')->constrained('interviews');
            $table->foreignId('interview_slot_id')->constrained('interview_slots');
            $table->foreignId('interviewer_id')->constrained('users');
            $table->json('criteria');
            $table->json('scores');
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interview_scores');
    }
};

