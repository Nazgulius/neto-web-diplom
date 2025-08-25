<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        if (!User::where('email', 'test@example.com')->exists()) {
          User::factory()->create([
              'name' => 'Test User',
              'email' => 'test@example.com',
              'password' => bcrypt('test'),
              'created_at' => now(),
              'updated_at' => now(),
          ]);
        }

        /*
        User::updateOrCreate(
          ['email' => 'test@example.com'], // условие поиска
          [
              'name' => 'Test User',
              'password' => bcrypt('password'),
              'email_verified_at' => now(),
          ]
        );
        */

      $this->call(HallsTableSeeder::class);
      $this->call(MoviesTableSeeder::class);
      $this->call(KinoSessionsTableSeeder::class);
      $this->call(SeatsTableSeeder::class);
      $this->call(TicketsTableSeeder::class);
      $this->call(SessionGlobalOpenTableSeeder::class);
    }
}
