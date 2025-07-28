<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Price;

class PricesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      Price::create([
        'hall_id' => 1,
        'seat_type' => 'Обычное',
        'amount' => 2,
      ]);
    }
}
