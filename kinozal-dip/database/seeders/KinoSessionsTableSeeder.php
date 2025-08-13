<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KinoSession;

class KinoSessionsTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // зал 1
    KinoSession::create([
      'movie_id' => 1,
      'hall_id' => 1,
      'start_time' => '10:00',
      // 'end_time' => now(),
      'price' => 2,
    ]);

    KinoSession::create([
      'movie_id' => 3,
      'hall_id' => 1,
      'start_time' => '12:20',
      'price' => 2,
    ]);

    KinoSession::create([
      'movie_id' => 1,
      'hall_id' => 1,
      'start_time' => '14:40',
      'price' => 4,
    ]);

    KinoSession::create([
      'movie_id' => 3,
      'hall_id' => 1,
      'start_time' => '17:00',
      'price' => 6,
    ]);

    KinoSession::create([
      'movie_id' => 1,
      'hall_id' => 1,
      'start_time' => '19:20',
      'price' => 8,
    ]);

    // Зал 2
    KinoSession::create([
      'movie_id' => 2,
      'hall_id' => 2,
      'start_time' => '10:30',
      'price' => 2,
    ]);

    KinoSession::create([
      'movie_id' => 3,
      'hall_id' => 2,
      'start_time' => '12:50',
      'price' => 2,
    ]);

    KinoSession::create([
      'movie_id' => 2,
      'hall_id' => 2,
      'start_time' => '15:10',
      'price' => 4,
    ]);

    KinoSession::create([
      'movie_id' => 3,
      'hall_id' => 2,
      'start_time' => '17:30',
      'price' => 6,
    ]);

    KinoSession::create([
      'movie_id' => 2,
      'hall_id' => 2,
      'start_time' => '19:50',
      'price' => 8,
    ]);
  }
}
