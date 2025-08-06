<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Seat;
use App\Models\Hall;


class SeatsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $hall = Hall::where('name', 'main')->first();

      for ($i = 1; $i <= 50; $i++) {
        if ($hall) {
          for ($i = 1; $i <= 50; $i++) {
            Seat::create([
                'hall_id' => $hall->id,
                'row' => ceil($i / 10),
                'number' => $i % 10 == 0 ? 10 : $i % 10,
                'type' => 'Обычное',
            ]);

          }
        }
      }

      
    }
}
