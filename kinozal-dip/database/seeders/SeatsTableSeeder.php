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
    $halls = Hall::all();

    foreach ($halls as $hall) {
      $rows = (int) $hall->rows;
      $seatsPerRow = (int) $hall->seats_per_row;

      for ($row = 1; $row <= $rows; $row++) {
        for ($num = 1; $num <= $seatsPerRow; $num++) {
          Seat::create([
            'hall_id' => $hall->id,
            'row' => $row,
            'number' => $num,
            'type' => 'Обычное',
          ]);
        }
      }
    }
  }
}
