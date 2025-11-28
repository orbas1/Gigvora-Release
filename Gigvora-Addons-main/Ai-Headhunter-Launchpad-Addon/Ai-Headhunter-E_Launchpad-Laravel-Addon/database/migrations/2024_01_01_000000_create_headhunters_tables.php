<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('headhunter_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('status')->default('pending')->index();
            $table->text('bio')->nullable();
            $table->json('industries')->nullable();
            $table->json('skills')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'status']);
        });

        Schema::create('headhunter_mandates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('headhunter_profile_id')->constrained('headhunter_profiles');
            $table->foreignId('organisation_id')->nullable()->constrained('organizations');
            $table->string('title');
            $table->string('location')->nullable();
            $table->string('fee_model')->nullable();
            $table->decimal('fee_amount', 10, 2)->nullable();
            $table->string('status')->default('open')->index();
            $table->json('requirements')->nullable();
            $table->timestamps();
            $table->index(['organisation_id', 'status']);
        });

        Schema::create('headhunter_candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('headhunter_profile_id')->constrained('headhunter_profiles');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->json('skills')->nullable();
            $table->json('experience')->nullable();
            $table->timestamps();
            $table->index(['user_id']);
        });

        Schema::create('headhunter_pipeline_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('headhunter_mandate_id')->constrained('headhunter_mandates');
            $table->foreignId('headhunter_candidate_id')->constrained('headhunter_candidates');
            $table->string('stage')->default('sourced')->index();
            $table->text('notes')->nullable();
            $table->timestamp('moved_at')->nullable();
            $table->timestamps();
            $table->unique(['headhunter_mandate_id', 'headhunter_candidate_id']);
        });

        Schema::create('headhunter_interviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('headhunter_pipeline_item_id')->constrained('headhunter_pipeline_items');
            $table->foreignId('scheduled_by')->constrained('users');
            $table->timestamp('scheduled_at')->index();
            $table->string('status')->default('scheduled')->index();
            $table->text('summary')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('headhunter_interviews');
        Schema::dropIfExists('headhunter_pipeline_items');
        Schema::dropIfExists('headhunter_candidates');
        Schema::dropIfExists('headhunter_mandates');
        Schema::dropIfExists('headhunter_profiles');
    }
};
