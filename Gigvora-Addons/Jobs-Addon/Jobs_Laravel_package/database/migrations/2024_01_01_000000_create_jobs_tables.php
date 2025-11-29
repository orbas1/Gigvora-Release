<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('company_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('headline')->nullable();
            $table->text('description')->nullable();
            $table->string('website')->nullable();
            $table->string('location')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('cover_path')->nullable();
            $table->timestamps();
        });

        Schema::create('candidate_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('headline')->nullable();
            $table->text('bio')->nullable();
            $table->string('location')->nullable();
            $table->json('skills')->nullable();
            $table->unsignedInteger('experience_years')->nullable();
            $table->string('resume_path')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->timestamps();
        });

        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('location')->nullable();
            $table->string('workplace_type')->nullable();
            $table->string('employment_type')->nullable();
            $table->decimal('salary_min', 12, 2)->nullable();
            $table->decimal('salary_max', 12, 2)->nullable();
            $table->string('currency', 3)->nullable();
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });

        Schema::create('jobs_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('job_bookmarks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });

        Schema::create('cover_letters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidate_id');
            $table->string('title');
            $table->longText('body');
            $table->timestamps();
        });

        Schema::create('cv_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidate_id');
            $table->string('title');
            $table->json('content');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_id');
            $table->unsignedBigInteger('candidate_id');
            $table->unsignedBigInteger('cover_letter_id')->nullable();
            $table->unsignedBigInteger('cv_template_id')->nullable();
            $table->unsignedInteger('screening_score')->nullable();
            $table->string('status')->default('applied');
            $table->text('notes')->nullable();
            $table->string('resume_path')->nullable();
            $table->timestamp('applied_at')->nullable();
            $table->timestamps();
        });

        Schema::create('ats_pipelines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('ats_stages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ats_pipeline_id');
            $table->string('name');
            $table->unsignedInteger('position')->default(0);
            $table->string('color')->nullable();
            $table->timestamps();
        });

        Schema::create('ats_stage_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_application_id');
            $table->unsignedBigInteger('ats_stage_id');
            $table->unsignedBigInteger('moved_by')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('moved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('screening_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_id');
            $table->string('question');
            $table->string('type')->default('text');
            $table->json('options')->nullable();
            $table->boolean('is_required')->default(true);
            $table->timestamps();
        });

        Schema::create('screening_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_application_id');
            $table->unsignedBigInteger('screening_question_id');
            $table->text('answer');
            $table->timestamps();
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('plan');
            $table->unsignedInteger('job_credits')->default(0);
            $table->timestamp('renews_at')->nullable();
            $table->string('status')->default('active');
            $table->string('payment_reference')->nullable();
            $table->timestamps();
        });

        Schema::create('interview_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_application_id');
            $table->timestamp('scheduled_at');
            $table->string('location')->nullable();
            $table->text('instructions')->nullable();
            $table->string('meeting_link')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interview_schedules');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('screening_answers');
        Schema::dropIfExists('screening_questions');
        Schema::dropIfExists('ats_stage_assignments');
        Schema::dropIfExists('ats_stages');
        Schema::dropIfExists('ats_pipelines');
        Schema::dropIfExists('job_applications');
        Schema::dropIfExists('cv_templates');
        Schema::dropIfExists('cover_letters');
        Schema::dropIfExists('job_bookmarks');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('jobs_categories');
        Schema::dropIfExists('candidate_profiles');
        Schema::dropIfExists('company_profiles');
    }
};
