<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('keyword_registry', function (Blueprint $table) {
            $table->id();
            $table->string('keyword');
            $table->string('normalized')->index();
            $table->string('source_type')->nullable()->index();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->string('country')->nullable()->index();
            $table->unsignedInteger('frequency')->default(1);
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();

            $table->unique(['normalized', 'source_type', 'source_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keyword_registry');
    }
};
