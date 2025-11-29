<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('host_id')->constrained('users');
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('scheduled_at');
            $table->integer('duration_minutes');
            $table->boolean('is_panel')->default(false);
            $table->string('location')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};

