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
        Schema::create('event', function (Blueprint $table) {
            $table->id('event_id');
            $table->string('name');
            $table->string('description');
            $table->string('location');
            $table->dateTime('date');
            $table->string('image_path');
            $table->string('category');
            $table->integer('participation_limit');
            $table->integer('creator_user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event');
    }
};
