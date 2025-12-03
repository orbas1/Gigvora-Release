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
        Schema::create('gigs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('author_id')->index();
            $table->string('title');
            $table->string('slug')->index();
            $table->string('country');
            $table->string('zipcode',100);
            $table->text('address')->nullable();
            $table->text('description')->nullable();
            $table->text('attachments')->nullable();
            $table->text('downloadable')->nullable();
            $table->tinyInteger('is_featured')->default(0)->index();
            $table->datetime('featured_expiry')->nullable();
            $table->enum('status', ['publish', 'draft'])->default('publish')->index();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('gigs', function (Blueprint $table) {
            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                $table->fullText('title');
                $table->fullText('country');
            } else {
                $table->index('title');
                $table->index('country');
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
        Schema::dropIfExists('gigs');
    }
};
