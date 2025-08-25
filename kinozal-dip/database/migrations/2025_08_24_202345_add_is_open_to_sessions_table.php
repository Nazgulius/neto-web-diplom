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
    Schema::create('sessionsGlobal', function (Blueprint $table) {
      $table->id();
      $table->string('key')->unique();
      $table->boolean('value')->default(true);
      $table->timestamps();
      // if (!Schema::hasColumn('sessions', 'id')) {
      //   $table->id();
      // }
      // if (!Schema::hasColumn('sessions', 'key')) {
      //   $table->string('key')->unique();
      // }

      // if (!Schema::hasColumn('sessions', 'value')) {
      //   $table->boolean('value')->default(true);
      // }
      // $table->string('key')->unique();
      // $table->boolean('value')->default(true);
      // $table->boolean('is_open')->default(false); // старый вариант
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('sessions', function (Blueprint $table) {
      //
    });
  }
};
