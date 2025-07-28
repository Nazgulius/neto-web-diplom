<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Hall;

class RoomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $hall = Hall::where('name', 'Main Hall')->first();

      if (!$hall) {
          $hall = Hall::create(['name' => 'Main Hall']);
      }

      Room::create([
        'hall_id' => $hall->id,
        'name' => 'Room 1',
      ]);
    }
}
