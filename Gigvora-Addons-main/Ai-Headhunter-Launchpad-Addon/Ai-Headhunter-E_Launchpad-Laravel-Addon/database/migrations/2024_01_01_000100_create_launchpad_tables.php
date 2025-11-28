<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('launchpad_programmes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')->constrained('users');
            $table->string('title');
            $table->string('category');
            $table->text('description')->nullable();
            $table->integer('estimated_hours')->default(0);
            $table->integer('estimated_weeks')->default(0);
            $table->boolean('reference_offered')->default(false);
            $table->boolean('qualification_offered')->default(false);
            $table->decimal('pay_reduction_percentage', 5, 2)->nullable();
            $table->string('status')->default('draft')->index();
            $table->timestamps();
            $table->index(['creator_id', 'status']);
        });

        Schema::create('launchpad_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('launchpad_programme_id')->constrained('launchpad_programmes');
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->integer('estimated_hours')->default(0);
            $table->timestamps();
            $table->index(['launchpad_programme_id', 'order']);
        });

        Schema::create('launchpad_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('launchpad_programme_id')->constrained('launchpad_programmes');
            $table->foreignId('user_id')->constrained('users');
            $table->string('status')->default('submitted')->index();
            $table->text('motivation')->nullable();
            $table->boolean('reference_issued')->default(false);
            $table->boolean('qualification_issued')->default(false);
            $table->integer('hours_gained')->default(0);
            $table->integer('weeks_gained')->default(0);
            $table->timestamps();
            $table->index(['user_id', 'status']);
        });

        Schema::create('launchpad_interviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('launchpad_application_id')->constrained('launchpad_applications');
            $table->foreignId('scheduled_by')->constrained('users');
            $table->timestamp('scheduled_at');
            $table->string('status')->default('scheduled')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('launchpad_interviews');
        Schema::dropIfExists('launchpad_applications');
        Schema::dropIfExists('launchpad_tasks');
        Schema::dropIfExists('launchpad_programmes');
    }
};
