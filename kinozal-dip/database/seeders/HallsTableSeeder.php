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
        'name' => 'main',
        'rows' => 10,
        'seats_per_row' => 10,
        'active' => true,
      ]);
    }
}
