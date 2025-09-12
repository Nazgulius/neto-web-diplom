<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hall;
use App\Models\KinoSession;
use App\Models\Seat;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HallController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    return Hall::all();
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $movie = Hall::create($request->all());
    return response()->json($movie, 201);
  }
  public function create(Request $request)
  {
    // Валидация входящих данных
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'rows' => 'required|integer|min:1',
      'seats_per_row' => 'required|integer|min:1',
      'amountStandart' => 'required|integer|min:1',
      'amountVip' => 'required|integer|min:1',
    ]);

    // Создание записи с массовым назначением
    $hall = Hall::create([
      'name' => $validated['name'],
      'rows' => $validated['rows'],
      'seats_per_row' => $validated['seats_per_row'],
      'amountStandart' => $validated['amountStandart'],
      'amountVip' => $validated['amountVip'],
    ]);

    // Заполнение сидений для созданного зала
    $rows = (int) $hall->rows;
    $seatsPerRow = (int) $hall->seats_per_row;

    // Опционально можно обернуть в транзакцию чтобы данные были согласованы
    DB::beginTransaction();
    try {
      for ($row = 1; $row <= $rows; $row++) {
        for ($num = 1; $num <= $seatsPerRow; $num++) {
          Seat::create([
            'hall_id' => $hall->id,
            'row' => $row,
            'number' => $num,
            'type' => 'standart',
          ]);
        }
      }
      DB::commit();
    } catch (\Throwable $e) {
      DB::rollBack();
      // Логируем ошибку и возвращаем сообщение об ошибке
      Log::error('Ошибка заполнения сидений после создания зала', ['hall_id' => $hall->id, 'error' => $e->getMessage()]);
      return response()->json(['error' => 'Не удалось заполнить сидения'], 500);
    }

    // Возвращаем созданный зал и количество созданных сидений
    // $totalSeats = $rows * $seatsPerRow;
    // return response()->json([
    //   'hall' => $hall,
    //   'created_seats' => $totalSeats,
    // ], 201);

    return response()->json(['success' => true, 'hall' => $hall], 201);
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    return Hall::findOrFail($id);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    $hall = Hall::findOrFail($id);
    $hall->update($request->all());
    return response()->json($hall);
  }

  public function updatePrices(Request $request)
  {
    $hall = Hall::find($request->hall_id);

    if (!$hall) {
      return response()->json(['error' => 'Зал не найден'], 404);
    }

    $hall->update([
      'amountStandart' => $request->prices['standart'],
      'amountVip' => $request->prices['vip']
    ]);

    return response()->json(['message' => 'Цены успешно обновлены'], 200);
  }

  public function updateSeats(Request $request)
  {
    $validatedData = $request->validate([
      'hall_id' => 'required|exists:halls,id',
      'rows' => 'required|integer|min:1',
      'seats_per_row' => 'required|integer|min:1',
      'seats' => 'required|array'
    ]);

    try {
      $hall = Hall::find($request->hall_id);

      if (!$hall) {
        return response()->json([
          'success' => false,
          'error' => 'Зал не найден'
        ], 404);
      }

      // Сохранение конфигурации
      $hall->update([
        'rows' => $request->rows,
        'seats_per_row' => $request->seats_per_row
      ]);

      // Логика сохранения конфигурации мест
      // ...

      return response()->json([
        'message' => 'Конфигурация сохранена'
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'error' => 'Произошла ошибка при сохранении',
        'message' => $e->getMessage()
      ], 500);
    }
  }

  // В контроллере
  // public function getHallConfig(Request $request, $hallId)
  // {
  //   try {
  //     $hall = Hall::findOrFail($hallId);

  //     $config = [
  //         'rows' => $hall->rows,
  //         'seats_per_row' => $hall->seats_per_row,
  //         'seats' => $hall->seats->map(function ($seat) {
  //             return [
  //                 'row' => $seat->row,
  //                 'number' => $seat->number,
  //                 'type' => $seat->type
  //             ];
  //         })->toArray()
  //     ];


  //     return response()->json($config, 200);
  //   } catch (ModelNotFoundException $e) {
  //     return response()->json(['error' => 'Зал не найден'], 404);
  //   } catch (\Exception $e) {
  //     return response()->json(['error' => 'Произошла ошибка'], 500);
  //   }
  // }
  public function getHallConfig(Request $request, $hallId)
  {
    try {
      $hall = Hall::findOrFail($hallId);

      $config = [
        'rows' => $hall->rows,
        'seats_per_row' => $hall->seats_per_row,
        'seats' => $hall->seats
          ->unique(function ($seat) {
            // Создаем уникальный ключ на основе номера ряда и места
            return $seat->row . '-' . $seat->number;
          })
          ->map(function ($seat) {
            return [
              'row' => $seat->row,
              'number' => $seat->number,
              'type' => $seat->type
            ];
          })
          ->toArray()
      ];

      return response()->json($config, 200);
    } catch (ModelNotFoundException $e) {
      return response()->json(['error' => 'Зал не найден'], 404);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Произошла ошибка'], 500);
    }
  }



  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $hall = Hall::find($id);
    if (!$hall) {
      return response()->json(['message' => 'Hall not found'], 404);
    }
    $hall->delete();

    // 204 No Content без тела
    return response()->json(null, 204);

    // Hall::destroy($id);
    // return response()->json(null, 204);
  }

  public function getSeatsStatus($hallId, $seanceId)
  {
    // Получить все места зала
    $seats = Seat::where('hall_id', $hallId)->get();

    // Получить занятые места для этого сеанса
    $bookedSeats = Ticket::where('seance_id', $seanceId)
      ->where('status', 'purchased') // или 'reserved'
      ->pluck('seat_id')
      ->toArray();

    // Формируем массив с информацией о каждом месте
    $seatsData = $seats->map(function ($seat) use ($bookedSeats) {
      return [
        'id' => $seat->id,
        'row' => $seat->row,
        'seat_number' => $seat->seat_number,
        'is_booked' => in_array($seat->id, $bookedSeats),
      ];
    });

    return response()->json($seatsData);
  }
}
