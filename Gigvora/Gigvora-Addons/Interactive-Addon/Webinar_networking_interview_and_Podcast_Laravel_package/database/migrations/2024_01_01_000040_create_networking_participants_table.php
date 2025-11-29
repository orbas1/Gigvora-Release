<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('networking_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('networking_session_id')->constrained('networking_sessions');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('current_partner_id')->nullable()->constrained('users');
            $table->integer('rotation_position')->default(1);
            $table->timestamp('joined_at')->nullable();
            $table->string('status')->default('registered');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('networking_participants');
    }
};

