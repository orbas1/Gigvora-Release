<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->morphs('ticketable');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->string('currency')->default('USD');
            $table->string('status')->default('available');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
