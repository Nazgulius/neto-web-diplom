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
    Schema::create('prices', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('hall_id');
      $table->string('seat_type'); // VIP или Обычное
      $table->decimal('amount', 8, 2);
      $table->timestamps();

      $table->foreign('hall_id')->references('id')->on('halls')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('prices');
  }
};
