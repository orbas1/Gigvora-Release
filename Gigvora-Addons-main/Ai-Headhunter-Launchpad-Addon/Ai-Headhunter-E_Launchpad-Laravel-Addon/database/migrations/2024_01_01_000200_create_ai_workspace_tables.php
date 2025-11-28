<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ai_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('tool');
            $table->string('status')->default('pending');
            $table->integer('prompt_tokens')->default(0);
            $table->integer('completion_tokens')->default(0);
            $table->integer('credit_cost')->default(0);
            $table->json('input')->nullable();
            $table->json('output')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'tool']);
            $table->index(['status']);
        });

        Schema::create('ai_byok_credentials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('provider');
            $table->string('api_key');
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'provider']);
        });

        Schema::create('ai_subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->json('limits')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('ai_user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('ai_subscription_plan_id')->constrained('ai_subscription_plans');
            $table->date('renews_at')->nullable();
            $table->string('status')->default('active')->index();
            $table->timestamps();
            $table->unique(['user_id', 'ai_subscription_plan_id']);
        });

        Schema::create('ai_usage_aggregates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->date('period_start');
            $table->date('period_end');
            $table->integer('tokens_used')->default(0);
            $table->integer('sessions_count')->default(0);
            $table->timestamps();
            $table->index(['user_id', 'period_start', 'period_end']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_usage_aggregates');
        Schema::dropIfExists('ai_user_subscriptions');
        Schema::dropIfExists('ai_subscription_plans');
        Schema::dropIfExists('ai_byok_credentials');
        Schema::dropIfExists('ai_sessions');
    }
};
