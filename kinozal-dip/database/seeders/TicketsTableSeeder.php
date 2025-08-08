<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ticket;

class TicketsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      Ticket::create([
        'session_id' => 1,
        'seat_id' => 1,
        'code' => 111,
        'status' => 'booked',
      ]);
    }
}
