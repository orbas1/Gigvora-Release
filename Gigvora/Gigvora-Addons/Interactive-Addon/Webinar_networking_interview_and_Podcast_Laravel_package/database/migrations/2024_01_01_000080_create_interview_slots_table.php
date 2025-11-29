<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('interview_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interview_id')->constrained('interviews');
            $table->foreignId('interviewer_id')->constrained('users');
            $table->foreignId('interviewee_id')->constrained('users');
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->string('status')->default('scheduled');
            $table->string('meeting_link')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interview_slots');
    }
};

