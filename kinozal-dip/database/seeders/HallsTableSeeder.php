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
        'active' => true,
        'amountStandart' => 10,
        'amountVip'=> 50,
        'vip_count'=> 6,
      ]);

      Hall::create([
        'name' => 'Second Hall',
        'rows' => 8,
        'seats_per_row' => 10,
        'active' => true,
        'amountStandart' => 10,
        'amountVip'=> 50,
        'vip_count'=> 4,
      ]);
    }
}
