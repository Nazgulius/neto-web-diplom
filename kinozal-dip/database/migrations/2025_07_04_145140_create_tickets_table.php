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
    Schema::create('tickets', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('session_id');
      $table->unsignedBigInteger('seat_id');
      $table->string('code')->unique();
      $table->string('status'); // бронирован, продан, отменен
      $table->timestamps();

      $table->foreign('session_id')->references('id')->on('sessions')->onDelete('cascade');
      $table->foreign('seat_id')->references('id')->on('seats')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('tickets');
  }
};
