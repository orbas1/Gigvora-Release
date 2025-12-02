<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('podcast_episodes', function (Blueprint $table) {
            $table->boolean('is_paid')->default(false)->after('is_public');
            $table->string('entitlement_type')->nullable()->after('is_paid');
            $table->unsignedInteger('price_cents')->nullable()->after('entitlement_type');
            $table->unsignedInteger('donation_suggested_cents')->nullable()->after('price_cents');
        });

        Schema::create('podcast_episode_transcripts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('podcast_episode_id')->constrained('podcast_episodes')->cascadeOnDelete();
            $table->string('language', 8)->default('en');
            $table->string('source')->nullable();
            $table->longText('content');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('podcast_episode_highlights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('podcast_episode_id')->constrained('podcast_episodes')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('starts_at_seconds');
            $table->unsignedInteger('ends_at_seconds')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('podcast_episode_entitlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('podcast_episode_id')->constrained('podcast_episodes')->cascadeOnDelete();
            $table->unsignedBigInteger('user_id');
            $table->string('entitlement_type');
            $table->string('source')->nullable();
            $table->timestamp('granted_at')->useCurrent();
            $table->timestamp('expires_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['podcast_episode_id', 'user_id', 'entitlement_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('podcast_episode_entitlements');
        Schema::dropIfExists('podcast_episode_highlights');
        Schema::dropIfExists('podcast_episode_transcripts');

        Schema::table('podcast_episodes', function (Blueprint $table) {
            $table->dropColumn(['is_paid', 'entitlement_type', 'price_cents', 'donation_suggested_cents']);
        });
    }
};

