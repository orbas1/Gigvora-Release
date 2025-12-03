<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('launchpad_application_task_progress', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('launchpad_application_id');
            $table->unsignedBigInteger('launchpad_task_id');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->foreign('launchpad_application_id', 'fk_lapp_task_prog_application')
                ->references('id')->on('launchpad_applications')->cascadeOnDelete();
            $table->foreign('launchpad_task_id', 'fk_lapp_task_prog_task')
                ->references('id')->on('launchpad_tasks')->cascadeOnDelete();
            $table->unique(['launchpad_application_id', 'launchpad_task_id'], 'uniq_lapp_task_prog');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('launchpad_application_task_progress');
    }
};
