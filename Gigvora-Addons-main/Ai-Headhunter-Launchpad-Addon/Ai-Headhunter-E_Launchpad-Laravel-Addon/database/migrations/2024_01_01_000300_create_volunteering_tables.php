<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('volunteering_opportunities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organisation_id')->nullable()->constrained('organisations');
            $table->foreignId('creator_id')->constrained('users');
            $table->string('title');
            $table->string('sector');
            $table->string('location')->nullable();
            $table->string('commitment')->nullable();
            $table->boolean('expenses_covered')->default(false);
            $table->boolean('verified')->default(false);
            $table->string('status')->default('draft');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('volunteering_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('volunteering_opportunity_id')->constrained('volunteering_opportunities');
            $table->foreignId('user_id')->constrained('users');
            $table->string('status')->default('submitted');
            $table->text('motivation')->nullable();
            $table->integer('hours_contributed')->default(0);
            $table->timestamps();
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('volunteering_applications');
        Schema::dropIfExists('volunteering_opportunities');
    }
};
