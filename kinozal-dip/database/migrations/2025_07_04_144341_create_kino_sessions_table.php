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
    Schema::create('kino_sessions', function (Blueprint $table) {
      $table->id();
      $table->integer('movie_id');
      $table->integer('hall_id');
      $table->time('start_time');
      // $table->time('end_time'); // времяокончания пока не нужно
      // $table->decimal('price');
      $table->timestamps();

      $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
      $table->foreign('hall_id')->references('id')->on('halls')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('kino_sessions');
  }
};
