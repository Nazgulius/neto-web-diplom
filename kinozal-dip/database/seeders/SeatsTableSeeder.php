<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Seat;
use App\Models\Hall;
use App\Models\KinoSession;


class SeatsTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $halls = Hall::all();
    $sessionsAll = KinoSession::all();

    // Определяем функцию для получения VIP-мест
    $getVipSeats = function ($totalSeats, $vipCount) {
      $vipSeats = [];
      $center = floor($totalSeats / 2);

      for ($i = 0; $i < $vipCount; $i++) {
        if ($i % 2 == 0) {
          $vipSeats[] = $center - floor($i / 2);
        } else {
          $vipSeats[] = $center + floor($i / 2) + 1;
        }
      }

      return array_unique($vipSeats);
    };

    foreach ($halls as $hall) {
      foreach ($sessionsAll as $session) {
        // сравниваем ID через свойства модели
        if ($session->hall_id === $hall->id) {
          $rows = (int)$hall->rows;
          $seatsPerRow = (int)$hall->seats_per_row;
          $vipCount = (int)$hall->vip_count;          

          for ($row = 1; $row <= $rows; $row++) {
            

            // Пропускаем первый и последний ряд для VIP-мест
            // $currentVipSeats = ($row === 1 || $row === $rows)
            //   ? []
            //   : $getVipSeats($seatsPerRow, $vipCount);
            if ($row === 1 || $row === $rows) {
              $currentVipSeats = [];
              
            } else {
              $currentVipSeats = $getVipSeats($seatsPerRow, $vipCount);
              
            }
            
            for ($num = 1; $num <= $seatsPerRow; $num++) {
              

              $seatType = in_array($num, $currentVipSeats)
                ? "VIP"
                : 'Обычное';
              
              Seat::create([
                'session_id' => $session->id,
                'hall_id' => $hall->id,
                'row' => $row,
                'number' => $num,
                'type' => $seatType,
              ]);
            }
          }
        }
      }
    }
  }
}

// старый вариант
// foreach ($halls as $hall) {
//   $rows = (int) $hall->rows;
//   $seatsPerRow = (int) $hall->seats_per_row;


//   foreach ($sessions as $session) {
//     for ($row = 1; $row <= $rows; $row++) {
//       for ($num = 1; $num <= $seatsPerRow; $num++) {
//         Seat::create([
//           'hall_id' => $hall->id,
//           'row' => $row,
//           'number' => $num,
//           'type' => 'Обычное',
//         ]);
//       }
//     }
//   }
// }