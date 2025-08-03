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

      \App\Models\Hall::create([
        'name' => 'Main Hall',
        'rows' => 10,
        'seats_per_row' => 10,
        'active' => true,
      ]);

      $this->call(RoomsTableSeeder::class);
      $this->call(SeatsTableSeeder::class);

      $this->call([MoviesTableSeeder::class]);
      $this->call(KinoSessionsTableSeeder::class);
    }
}
