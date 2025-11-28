<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('advertisers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('billing_email');
            $table->decimal('daily_spend_limit', 12, 2)->nullable();
            $table->decimal('lifetime_spend_limit', 12, 2)->nullable();
            $table->decimal('wallet_balance', 12, 2)->default(0);
            $table->string('status')->default('active')->index();
            $table->foreignId('affiliate_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advertisers');
    }
};
