<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('gig_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->string('name');
            $table->text('image')->nullable();
            $table->string('slug')->index();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'deactive'])->default('active')->index();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('gig_categories', function (Blueprint $table) {
            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                $table->fullText('name');
                $table->fullText('description');
            } else {
                $table->index('name');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gig_categories');
    }
};
