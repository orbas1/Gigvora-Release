<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('keyword_prices', function (Blueprint $table) {
            $table->id();
            $table->string('keyword')->unique();
            $table->decimal('cpc', 8, 2)->default(0);
            $table->decimal('cpa', 8, 2)->default(0);
            $table->decimal('cpm', 8, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keyword_prices');
    }
};
