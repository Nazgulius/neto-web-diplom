<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Movie;

class MoviesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      //Movie::factory()->count(10)->create(); // Создаст 10 фильмов
      // Или вручную:
      Movie::create([
          'title' => 'Фильм 1',
          'description' => 'Описание фильма 1',
          'duration' => 120, // минуты
      ]);
    }
}
