<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KinoSession;
use Carbon\Carbon;

class KinoSessionsTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $baseDate = Carbon::now();
    $allKinoSessions = [
      // зал 1
      [
        'movie_id' => 1,
        'hall_id' => 1,
        'start_datetime' => $baseDate->clone()->setTime(10, 0, 0),
      ],
      [
        'movie_id' => 3,
        'hall_id' => 1,
        'start_datetime' => $baseDate->clone()->setTime(12, 20, 0),      
      ],
      [
        'movie_id' => 1,
        'hall_id' => 1,
        'start_datetime' => $baseDate->clone()->setTime(14, 40, 0),
      ],
      [
        'movie_id' => 3,
        'hall_id' => 1,
        'start_datetime' => $baseDate->clone()->setTime(17, 0, 0),
      ],
      [
        'movie_id' => 1,
        'hall_id' => 1,
        'start_datetime' => $baseDate->clone()->setTime(19, 20, 0),
      ],
      // Зал 2
      [
        'movie_id' => 2,
        'hall_id' => 2,
        'start_datetime' => $baseDate->clone()->setTime(10, 30, 0),
      ],
  
      [
        'movie_id' => 3,
        'hall_id' => 2,
        'start_datetime' => $baseDate->clone()->setTime(12, 50, 0),
      ],
  
      [
        'movie_id' => 2,
        'hall_id' => 2,
        'start_datetime' => $baseDate->clone()->setTime(15, 10, 0),
      ],
  
      [
        'movie_id' => 3,
        'hall_id' => 2,
        'start_datetime' => $baseDate->clone()->setTime(17, 30, 0),
      ],
  
      [
        'movie_id' => 2,
        'hall_id' => 2,
        'start_datetime' => $baseDate->clone()->setTime(19, 50, 0),
      ],
    ];

    KinoSession::insert($allKinoSessions);
    
    // зал 1
    // KinoSession::create([
    //   'movie_id' => 1,
    //   'hall_id' => 1,
    //   'start_datetime' => now()->setTime(10, 0, 0),
    // ]);

    // KinoSession::create([
    //   'movie_id' => 3,
    //   'hall_id' => 1,
    //   'start_datetime' => now()->setTime(12, 20, 0),      
    // ]);

    // KinoSession::create([
    //   'movie_id' => 1,
    //   'hall_id' => 1,
    //   'start_datetime' => now()->setTime(14, 40, 0),
    // ]);

    // KinoSession::create([
    //   'movie_id' => 3,
    //   'hall_id' => 1,
    //   'start_datetime' => now()->setTime(17, 0, 0),
    // ]);

    // KinoSession::create([
    //   'movie_id' => 1,
    //   'hall_id' => 1,
    //   'start_datetime' => now()->setTime(19, 20, 0),
    // ]);

    // Зал 2
    // KinoSession::create([
    //   'movie_id' => 2,
    //   'hall_id' => 2,
    //   'start_datetime' => now()->setTime(10, 30, 0),
    // ]);

    // KinoSession::create([
    //   'movie_id' => 3,
    //   'hall_id' => 2,
    //   'start_datetime' => now()->setTime(12, 50, 0),
    // ]);

    // KinoSession::create([
    //   'movie_id' => 2,
    //   'hall_id' => 2,
    //   'start_datetime' => now()->setTime(15, 10, 0),
    // ]);

    // KinoSession::create([
    //   'movie_id' => 3,
    //   'hall_id' => 2,
    //   'start_datetime' => now()->setTime(17, 30, 0),
    // ]);

    // KinoSession::create([
    //   'movie_id' => 2,
    //   'hall_id' => 2,
    //   'start_datetime' => now()->setTime(19, 50, 0),
    // ]);
  }
}
