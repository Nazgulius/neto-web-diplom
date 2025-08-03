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
      KinoSession::create([
        'movie_id' => 1,
        'hall_id' => 1,
        'start_time' => '10:00',
        // 'end_time' => now(),
        'price' => 2,
      ]);

      KinoSession::create([
        'movie_id' => 1,
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
        'movie_id' => 1,
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
    }
}
