<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pro_network_connection_caches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('connection_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('degree');
            $table->json('connection_path')->nullable();
            $table->unsignedSmallInteger('mutual_count')->default(0);
            $table->unsignedSmallInteger('strength')->default(0);
            $table->timestamp('calculated_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'connection_id']);
        });

        Schema::create('pro_network_mutual_connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('target_user_id')->constrained('users')->cascadeOnDelete();
            $table->json('mutual_user_ids');
            $table->unsignedSmallInteger('mutual_count');
            $table->timestamp('calculated_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'target_user_id']);
        });

        Schema::create('pro_network_network_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->unique();
            $table->unsignedInteger('first_degree_count')->default(0);
            $table->unsignedInteger('second_degree_count')->default(0);
            $table->unsignedInteger('third_degree_count')->default(0);
            $table->unsignedInteger('mutual_count')->default(0);
            $table->json('suggestions')->nullable();
            $table->timestamp('calculated_at')->nullable();
            $table->timestamps();
        });

        Schema::create('pro_network_professional_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->unique();
            $table->string('headline')->nullable();
            $table->string('tagline')->nullable();
            $table->string('location')->nullable();
            $table->json('top_skills')->nullable();
            $table->boolean('available_for_work')->default(false);
            $table->string('public_url')->nullable()->unique();
            $table->string('share_hash')->nullable();
            $table->unsignedInteger('connections_count')->default(0);
            $table->text('activity_summary')->nullable();
            $table->json('interests')->nullable();
            $table->string('visibility')->default('public');
            $table->timestamps();
        });

        Schema::create('pro_network_profile_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('proficiency')->nullable();
            $table->boolean('is_top_five')->default(false);
            $table->unsignedSmallInteger('weight')->default(0);
            $table->timestamps();
            $table->unique(['user_id', 'name']);
        });

        Schema::create('pro_network_profile_certifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('authority')->nullable();
            $table->string('license_number')->nullable();
            $table->string('verification_url')->nullable();
            $table->date('issued_at')->nullable();
            $table->date('expires_at')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('pro_network_profile_work_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('company_name');
            $table->string('employment_type')->nullable();
            $table->string('location')->nullable();
            $table->date('started_at')->nullable();
            $table->date('ended_at')->nullable();
            $table->boolean('is_current')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('pro_network_profile_education_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('institution');
            $table->string('degree')->nullable();
            $table->string('field')->nullable();
            $table->date('started_at')->nullable();
            $table->date('ended_at')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('pro_network_profile_references', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('reference_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('relationship')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('statement')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('pro_network_profile_background_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('status')->default('pending');
            $table->string('provider')->nullable();
            $table->string('reference')->nullable();
            $table->timestamp('checked_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('pro_network_profile_interests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('interest');
            $table->unsignedSmallInteger('weight')->default(0);
            $table->timestamps();
            $table->unique(['user_id', 'interest']);
        });

        Schema::create('pro_network_profile_opportunities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('type');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('rate', 12, 2)->nullable();
            $table->string('currency', 3)->nullable();
            $table->string('status')->default('open');
            $table->timestamps();
        });

        Schema::create('pro_network_company_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->cascadeOnDelete();
            $table->string('headline')->nullable();
            $table->string('industry')->nullable();
            $table->string('location')->nullable();
            $table->string('website')->nullable();
            $table->json('metadata')->nullable();
            $table->unsignedInteger('employee_count')->default(0);
            $table->timestamps();
        });

        Schema::create('pro_network_company_employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_profile_id')->constrained('pro_network_company_profiles')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('role_title')->nullable();
            $table->date('started_at')->nullable();
            $table->date('ended_at')->nullable();
            $table->boolean('is_current')->default(true);
            $table->timestamps();
            $table->unique(['company_profile_id', 'user_id']);
        });

        Schema::create('pro_network_marketplace_escrows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('marketplace_orders')->cascadeOnDelete()->unique();
            $table->string('status')->default('pending');
            $table->decimal('amount', 14, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('delivery_method')->default('delivery');
            $table->string('delivery_notes')->nullable();
            $table->string('escrow_reference')->nullable();
            $table->timestamp('held_at')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();
        });

        Schema::create('pro_network_marketplace_milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escrow_id')->constrained('pro_network_marketplace_escrows')->cascadeOnDelete();
            $table->string('title');
            $table->decimal('amount', 14, 2);
            $table->string('status')->default('pending');
            $table->timestamp('due_at')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();
        });

        Schema::create('pro_network_marketplace_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escrow_id')->constrained('pro_network_marketplace_escrows')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type');
            $table->decimal('amount', 14, 2);
            $table->string('currency', 3)->default('USD');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('pro_network_marketplace_disputes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escrow_id')->constrained('pro_network_marketplace_escrows')->cascadeOnDelete();
            $table->foreignId('raised_by')->constrained('users')->cascadeOnDelete();
            $table->text('reason');
            $table->string('status')->default('open');
            $table->text('resolution_notes')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('pro_network_marketplace_dispute_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dispute_id')->constrained('pro_network_marketplace_disputes')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('message');
            $table->json('attachments')->nullable();
            $table->timestamps();
        });

        Schema::create('pro_network_live_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('host_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default('scheduled');
            $table->json('guest_user_ids')->nullable();
            $table->unsignedInteger('likes_count')->default(0);
            $table->decimal('donations_total', 14, 2)->default(0);
            $table->string('chat_channel')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->string('recording_path')->nullable();
            $table->timestamps();
        });

        Schema::create('pro_network_live_session_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('live_session_id')->constrained('pro_network_live_sessions')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('role')->default('guest');
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('left_at')->nullable();
            $table->timestamps();
            $table->unique(['live_session_id', 'user_id']);
        });

        Schema::create('pro_network_reactions', function (Blueprint $table) {
            $table->id();
            $table->morphs('reactable');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('type');
            $table->integer('weight')->default(1);
            $table->timestamps();
            $table->unique(['reactable_id', 'reactable_type', 'user_id']);
        });

        Schema::create('pro_network_reaction_aggregates', function (Blueprint $table) {
            $table->id();
            $table->morphs('reactable');
            $table->json('counts')->nullable();
            $table->integer('dislikes')->default(0);
            $table->timestamps();
        });

        Schema::create('pro_network_profile_reaction_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->unique();
            $table->integer('like_score')->default(0);
            $table->integer('dislike_count')->default(0);
            $table->json('reaction_breakdown')->nullable();
            $table->timestamps();
        });

        Schema::create('pro_network_hashtags', function (Blueprint $table) {
            $table->id();
            $table->string('tag')->unique();
            $table->string('normalized')->unique();
            $table->unsignedInteger('usage_count')->default(0);
            $table->timestamps();
        });

        Schema::create('pro_network_hashtaggables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hashtag_id')->constrained('pro_network_hashtags')->cascadeOnDelete();
            $table->morphs('hashtaggable');
            $table->timestamps();
            $table->unique(['hashtag_id', 'hashtaggable_id', 'hashtaggable_type'], 'hashtaggable_unique');
        });

        Schema::create('pro_network_music_tracks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('artist')->nullable();
            $table->unsignedInteger('duration_seconds')->default(0);
            $table->string('license')->nullable();
            $table->string('storage_disk')->nullable();
            $table->string('storage_path')->nullable();
            $table->string('genre')->nullable();
            $table->string('mood')->nullable();
            $table->timestamps();
        });

        Schema::create('pro_network_story_metadata', function (Blueprint $table) {
            $table->id();
            $table->foreignId('story_id')->constrained('stories')->cascadeOnDelete();
            $table->json('overlays')->nullable();
            $table->json('filters')->nullable();
            $table->json('stickers')->nullable();
            $table->json('links')->nullable();
            $table->foreignId('music_track_id')->nullable()->constrained('pro_network_music_tracks')->nullOnDelete();
            $table->foreignId('live_session_id')->nullable()->constrained('pro_network_live_sessions')->nullOnDelete();
            $table->timestamps();
            $table->unique('story_id');
        });

        Schema::create('pro_network_security_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type');
            $table->string('ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('severity')->default('info');
            $table->json('context')->nullable();
            $table->timestamps();
        });

        Schema::create('pro_network_moderation_queue', function (Blueprint $table) {
            $table->id();
            $table->morphs('moderatable');
            $table->string('reason');
            $table->string('status')->default('pending');
            $table->json('flags')->nullable();
            $table->foreignId('actioned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('pro_network_bad_words', function (Blueprint $table) {
            $table->id();
            $table->string('phrase')->unique();
            $table->string('severity')->default('medium');
            $table->string('replacement')->nullable();
            $table->timestamps();
        });

        Schema::create('pro_network_bad_word_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('action');
            $table->json('applies_to')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('pro_network_file_scans', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('file_hash')->nullable();
            $table->string('scanner_name')->nullable();
            $table->string('status')->default('pending');
            $table->json('details')->nullable();
            $table->timestamp('scanned_at')->nullable();
            $table->timestamps();
        });

        Schema::create('pro_network_analytics_events', function (Blueprint $table) {
            $table->id();
            $table->string('event');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->json('properties')->nullable();
            $table->string('ip')->nullable();
            $table->timestamps();
        });

        Schema::create('pro_network_analytics_metrics', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type');
            $table->unsignedBigInteger('entity_id');
            $table->string('metric');
            $table->unsignedBigInteger('value')->default(0);
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->unique(['entity_type', 'entity_id', 'metric'], 'analytics_metric_unique');
        });

        Schema::create('pro_network_account_types', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('features')->nullable();
            $table->timestamps();
        });

        Schema::create('pro_network_user_account_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('account_type_id')->constrained('pro_network_account_types')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'account_type_id']);
        });

        Schema::create('pro_network_user_feature_flags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('feature');
            $table->boolean('enabled')->default(false);
            $table->timestamps();
            $table->unique(['user_id', 'feature']);
        });

        Schema::create('pro_network_age_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->unique();
            $table->string('status')->default('pending');
            $table->string('provider')->nullable();
            $table->string('provider_reference')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();
        });

        Schema::create('pro_network_age_verification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('age_verification_id')->constrained('pro_network_age_verifications')->cascadeOnDelete();
            $table->string('event');
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::create('pro_network_newsletter_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('email');
            $table->boolean('subscribed')->default(true);
            $table->string('source')->nullable();
            $table->string('locale')->nullable();
            $table->timestamps();
            $table->unique('email');
        });

        Schema::create('pro_network_invite_contributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inviter_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('invitee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('post_id')->nullable()->constrained('posts')->nullOnDelete();
            $table->string('role')->nullable();
            $table->string('status')->default('pending');
            $table->text('message')->nullable();
            $table->timestamps();
        });

        Schema::create('pro_network_post_enhancements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
            $table->string('type');
            $table->json('payload')->nullable();
            $table->timestamps();
            $table->unique('post_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pro_network_post_enhancements');
        Schema::dropIfExists('pro_network_invite_contributions');
        Schema::dropIfExists('pro_network_newsletter_subscriptions');
        Schema::dropIfExists('pro_network_age_verification_logs');
        Schema::dropIfExists('pro_network_age_verifications');
        Schema::dropIfExists('pro_network_user_feature_flags');
        Schema::dropIfExists('pro_network_user_account_types');
        Schema::dropIfExists('pro_network_account_types');
        Schema::dropIfExists('pro_network_analytics_metrics');
        Schema::dropIfExists('pro_network_analytics_events');
        Schema::dropIfExists('pro_network_file_scans');
        Schema::dropIfExists('pro_network_bad_word_rules');
        Schema::dropIfExists('pro_network_bad_words');
        Schema::dropIfExists('pro_network_moderation_queue');
        Schema::dropIfExists('pro_network_security_events');
        Schema::dropIfExists('pro_network_music_tracks');
        Schema::dropIfExists('pro_network_hashtaggables');
        Schema::dropIfExists('pro_network_hashtags');
        Schema::dropIfExists('pro_network_profile_reaction_scores');
        Schema::dropIfExists('pro_network_reaction_aggregates');
        Schema::dropIfExists('pro_network_reactions');
        Schema::dropIfExists('pro_network_live_session_participants');
        Schema::dropIfExists('pro_network_live_sessions');
        Schema::dropIfExists('pro_network_story_metadata');
        Schema::dropIfExists('pro_network_marketplace_dispute_messages');
        Schema::dropIfExists('pro_network_marketplace_disputes');
        Schema::dropIfExists('pro_network_marketplace_transactions');
        Schema::dropIfExists('pro_network_marketplace_milestones');
        Schema::dropIfExists('pro_network_marketplace_escrows');
        Schema::dropIfExists('pro_network_company_employees');
        Schema::dropIfExists('pro_network_company_profiles');
        Schema::dropIfExists('pro_network_profile_opportunities');
        Schema::dropIfExists('pro_network_profile_interests');
        Schema::dropIfExists('pro_network_profile_background_checks');
        Schema::dropIfExists('pro_network_profile_references');
        Schema::dropIfExists('pro_network_profile_education_histories');
        Schema::dropIfExists('pro_network_profile_work_histories');
        Schema::dropIfExists('pro_network_profile_certifications');
        Schema::dropIfExists('pro_network_profile_skills');
        Schema::dropIfExists('pro_network_professional_profiles');
        Schema::dropIfExists('pro_network_network_metrics');
        Schema::dropIfExists('pro_network_mutual_connections');
        Schema::dropIfExists('pro_network_connection_caches');
    }
};
