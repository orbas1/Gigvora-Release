<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('utilities_calendar_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('source', 64)->index();
            $table->string('source_id', 64);
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('starts_at')->index();
            $table->timestamp('ends_at')->nullable();
            $table->string('location')->nullable();
            $table->string('status', 32)->default('scheduled')->index();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'source', 'source_id'], 'utilities_calendar_events_user_source_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('utilities_calendar_events');
    }
};

