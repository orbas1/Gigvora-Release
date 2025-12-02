<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gig_faqs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gig_id')->index();
            $table->text('question');
            $table->text('answer');
            $table->timestamps();
        });

        Schema::table('gig_faqs', function (Blueprint $table) {
            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                $table->fullText('question');
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
        Schema::dropIfExists('gig_faqs');
    }
};
