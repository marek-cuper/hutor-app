<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
        #    $table->unsignedBigInteger('up_votes');
        #    $table->unsignedBigInteger('down_votes');
        #    $table->unsignedBigInteger('watched');
        #    $table->unsignedBigInteger('openned');
        #    $table->unsignedBigInteger('creator');
            $table->string('title');
            $table->text('text');
            $table->string('image_name')->nullable();
            $table->string('poll_text')->nullable();
        #    $table->unsignedBigInteger('pool_id')->nullable();
        #    $table->unsignedBigInteger('location_id')->nullable();
        #    $table->unsignedBigInteger('comment_section_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
