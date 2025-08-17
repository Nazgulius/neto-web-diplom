<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Hall;

class HallsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      Hall::create([
        'name' => 'Main Hall',
        'rows' => 10,
        'seats_per_row' => 10,        
        // 'layout' => [
        //   'rows' => 10,
        //   'seats_per_row' => 10,
        //   'layout_type' => 'square', 
        //   'additional_info' => 'места расположены квадратом'
        // ],
        'active' => true,
        'amountStandart' => 10,
        'amountVip'=> 50,
      ]);

      Hall::create([
        'name' => 'Second Hall',
        'rows' => 10,
        'seats_per_row' => 10,
        // 'layout' => [
        //   'rows' => 10,
        //   'seats_per_row' => 10,
        //   'layout_type' => 'square', 
        //   'additional_info' => 'места расположены квадратом'
        // ],
        'active' => true,
        'amountStandart' => 10,
        'amountVip'=> 50,
      ]);
    }
}
