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
    $allMovies =[
      [
        'title' => 'Звёздные войны XXIII: Атака клонированных клонов',
        'description' => 'Две сотни лет назад малороссийские хутора разоряла шайка нехристей-ляхов во главе с могущественным колдуном.',
        'duration' => 130, // минуты
        'country' => 'США',
        'image_url' => "/src/client/poster1.jpg",
      ],  
      [
        'title' => 'Альфа',
        'description' => '20 тысяч лет назад Земля была холодным и неуютным местом, в котором смерть подстерегала человека на каждом шагу.',
        'duration' => 96, // минуты
        'country' => 'Франция',
        'image_url' => "/src/client/poster2.jpg",
      ],  
      [
        'title' => 'Хищник',
        'description' => 'Самые опасные хищники Вселенной, прибыв из глубин космоса, высаживаются на улицах маленького городка, чтобы начать свою кровавую охоту. Генетически модернизировав себя с помощью ДНК других видов, охотники стали ещё сильнее, умнее и беспощаднее.',
        'duration' => 101, // минуты
        'country' => 'Канада, США',
        'image_url' => "/src/client/poster2.jpg",
      ],
    ];

    Movie::insert($allMovies);

    // Movie::create([
    //   'title' => 'Звёздные войны XXIII: Атака клонированных клонов',
    //   'description' => 'Две сотни лет назад малороссийские хутора разоряла шайка нехристей-ляхов во главе с могущественным колдуном.',
    //   'duration' => 130, // минуты
    //   'country' => 'США',
    //   'image_url' => "/src/client/poster1.jpg",
    // ]);

    // Movie::create([
    //   'title' => 'Альфа',
    //   'description' => '20 тысяч лет назад Земля была холодным и неуютным местом, в котором смерть подстерегала человека на каждом шагу.',
    //   'duration' => 96, // минуты
    //   'country' => 'Франция',
    //   'image_url' => "/src/client/poster2.jpg",
    // ]);

    // Movie::create([
    //   'title' => 'Хищник',
    //   'description' => 'Самые опасные хищники Вселенной, прибыв из глубин космоса, высаживаются на улицах маленького городка, чтобы начать свою кровавую охоту. Генетически модернизировав себя с помощью ДНК других видов, охотники стали ещё сильнее, умнее и беспощаднее.',
    //   'duration' => 101, // минуты
    //   'country' => 'Канада, США',
    //   'image_url' => "/src/client/poster2.jpg",
    // ]);
  }
}
