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
        Schema::create('gig_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gig_id')->index();
            $table->string('tag_name');
            $table->timestamps();
        });

        Schema::table('gig_tags', function (Blueprint $table) {
            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                $table->fullText('tag_name');
            } else {
                $table->index('tag_name');
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
        Schema::dropIfExists('gig_tags');
    }
};
