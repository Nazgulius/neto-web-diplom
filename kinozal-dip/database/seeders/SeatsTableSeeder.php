<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Seat;
use App\Models\Room;


class SeatsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $room = Room::where('name', 'Room 1')->first();

      for ($i = 1; $i <= 50; $i++) {
        if ($room) {
          Seat::create([
              'room_id' => $room->id,
              'row' => ceil($i / 10),
              'number' => $i % 10 + 1,
              'type' => 'Обычное',
          ]);
        }
      }
    }
}
