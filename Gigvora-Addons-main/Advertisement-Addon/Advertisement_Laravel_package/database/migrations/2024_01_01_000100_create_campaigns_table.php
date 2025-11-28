<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advertiser_id')->constrained('advertisers');
            $table->string('title');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->decimal('budget', 12, 2);
            $table->enum('bidding', ['click', 'view', 'conversion']);
            $table->string('status')->default('draft');
            $table->decimal('spend', 12, 2)->default(0);
            $table->string('placement');
            $table->string('objective')->nullable();
            $table->unsignedBigInteger('targeting_reach')->default(0);
            $table->string('approval_state')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
