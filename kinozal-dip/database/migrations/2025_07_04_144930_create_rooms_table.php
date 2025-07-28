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
    Schema::create('rooms', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->unsignedBigInteger('hall_id');
      $table->json('layout')->nullable(); // структура комнаты, например, расположение мест
      $table->timestamps();

      $table->foreign('hall_id')->references('id')->on('halls')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('rooms');
  }
};
