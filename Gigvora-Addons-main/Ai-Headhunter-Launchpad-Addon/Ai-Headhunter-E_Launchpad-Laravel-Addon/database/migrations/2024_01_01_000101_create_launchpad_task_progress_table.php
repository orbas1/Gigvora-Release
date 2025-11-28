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
            $table->foreignId('launchpad_application_id')->constrained('launchpad_applications');
            $table->foreignId('launchpad_task_id')->constrained('launchpad_tasks');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->unique(['launchpad_application_id', 'launchpad_task_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('launchpad_application_task_progress');
    }
};
