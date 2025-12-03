<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('networking_contact_exchanges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('networking_session_id')->constrained('networking_sessions');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('partner_id')->constrained('users');
            $table->boolean('starred')->default(false);
            $table->timestamp('follow_up_at')->nullable();
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['networking_session_id', 'user_id', 'partner_id'], 'uniq_network_contact_exchange');
            $table->index(['networking_session_id', 'user_id'], 'ix_network_contact_session_user');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('networking_contact_exchanges');
    }
};
