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
    Schema::create('halls', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->integer('rows');
      $table->integer('seats_per_row');
      // $table->json('layout')->nullable(); // структура комнаты, расположение мест
      $table->boolean('active')->default(true);
      $table->decimal('amountStandart', 8, 2);
      $table->decimal('amountVip', 8, 2);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('halls');
  }
};