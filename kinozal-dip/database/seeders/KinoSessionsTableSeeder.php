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
        'start_time' => now(),
        'end_time' => now() + 60,
        'price' => 2,
      ]);
    }
}
