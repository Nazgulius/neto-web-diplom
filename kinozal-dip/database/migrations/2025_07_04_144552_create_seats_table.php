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
    Schema::create('seats', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('room_id');
      $table->integer('row');
      $table->integer('number');
      $table->string('type')->default('Обычное'); // VIP или Обычное
      $table->timestamps();
      $table->boolean('taken')->default(false);

      $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('seats');
  }
};
