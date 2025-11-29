<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('project_freelancers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->string('freelancer');
            $table->string('role')->default('contributor');
            $table->timestamps();
        });

        Schema::create('project_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->string('title');
            $table->string('assignee')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('due_date')->nullable();
            $table->decimal('hours_logged', 8, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('project_milestones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->string('title');
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending');
            $table->timestamp('due_date')->nullable();
            $table->timestamps();
        });

        Schema::create('project_submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('milestone_id')->nullable();
            $table->text('notes');
            $table->string('attachment_url')->nullable();
            $table->string('status')->default('submitted');
            $table->timestamps();
        });

        Schema::create('project_invitations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->string('freelancer');
            $table->text('message')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        Schema::create('project_time_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->string('freelancer');
            $table->decimal('hours', 8, 2);
            $table->text('note')->nullable();
            $table->timestamp('logged_at');
            $table->timestamps();
        });

        Schema::create('project_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->decimal('rating', 2, 1);
            $table->text('comment')->nullable();
            $table->string('author');
            $table->timestamps();
        });

        Schema::create('gig_timeline_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gig_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('occurred_at');
            $table->timestamps();
        });

        Schema::create('gig_faqs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gig_id');
            $table->text('question');
            $table->text('answer');
            $table->timestamps();
        });

        Schema::create('gig_addons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gig_id');
            $table->string('title');
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });

        Schema::create('gig_packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gig_id');
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->integer('delivery_time');
            $table->timestamps();
        });

        Schema::create('gig_requirements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gig_id');
            $table->text('prompt');
            $table->timestamps();
        });

        Schema::create('gig_change_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gig_id');
            $table->string('requester');
            $table->text('notes');
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        Schema::create('gig_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gig_id');
            $table->decimal('rating', 2, 1);
            $table->text('comment')->nullable();
            $table->string('author');
            $table->timestamps();
        });

        Schema::create('profile_portfolios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('link')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->boolean('featured')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('profile_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('reviewer')->nullable();
            $table->decimal('rating', 2, 1);
            $table->text('comment')->nullable();
            $table->string('reference')->nullable();
            $table->timestamps();
        });

        Schema::create('profile_educations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('institution');
            $table->string('degree')->nullable();
            $table->string('field')->nullable();
            $table->integer('start_year')->nullable();
            $table->integer('end_year')->nullable();
            $table->timestamps();
        });

        Schema::create('profile_certifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('issuer')->nullable();
            $table->string('credential_url')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        Schema::create('custom_gigs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('buyer');
            $table->text('scope')->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();
        });

        Schema::create('dispute_stages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dispute_id');
            $table->string('stage');
            $table->text('notes')->nullable();
            $table->text('decision')->nullable();
            $table->timestamps();
        });

        Schema::create('freelance_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type')->default('freelancer');
            $table->timestamps();
        });

        Schema::create('freelance_tag_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tag_id');
            $table->unsignedBigInteger('assignable_id');
            $table->string('assignable_type');
            $table->timestamps();
        });

        Schema::create('escrow_actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('escrow_id');
            $table->string('type');
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('actor')->nullable();
            $table->string('decision')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        if (!Schema::hasColumn('escrows', 'released_amount')) {
            Schema::table('escrows', function (Blueprint $table) {
                $table->decimal('released_amount', 10, 2)->default(0);
            });
        }
    }

    public function down(): void
    {
        Schema::table('escrows', function (Blueprint $table) {
            if (Schema::hasColumn('escrows', 'released_amount')) {
                $table->dropColumn('released_amount');
            }
        });

        Schema::dropIfExists('escrow_actions');
        Schema::dropIfExists('dispute_stages');
        Schema::dropIfExists('freelance_tag_assignments');
        Schema::dropIfExists('freelance_tags');
        Schema::dropIfExists('custom_gigs');
        Schema::dropIfExists('gig_reviews');
        Schema::dropIfExists('gig_change_requests');
        Schema::dropIfExists('gig_requirements');
        Schema::dropIfExists('gig_packages');
        Schema::dropIfExists('gig_addons');
        Schema::dropIfExists('gig_faqs');
        Schema::dropIfExists('gig_timeline_items');
        Schema::dropIfExists('profile_certifications');
        Schema::dropIfExists('profile_educations');
        Schema::dropIfExists('profile_reviews');
        Schema::dropIfExists('profile_portfolios');
        Schema::dropIfExists('project_reviews');
        Schema::dropIfExists('project_time_logs');
        Schema::dropIfExists('project_invitations');
        Schema::dropIfExists('project_submissions');
        Schema::dropIfExists('project_milestones');
        Schema::dropIfExists('project_tasks');
        Schema::dropIfExists('project_freelancers');
    }
};
