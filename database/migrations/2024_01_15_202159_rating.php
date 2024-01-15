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
        Schema::create('rating', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('event_id')->unsigned();
            $table->integer('rating');
            $table->timestamps();
        });

        Schema::table('rating', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('user_id')
                ->on('user')->onDelete('cascade');
            $table->foreign('event_id')
                ->references('event_id')
                ->on('event')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rating');
    }
};
