<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KinoSession;
use App\Models\Seat;
use App\Models\Hall;
use Illuminate\Support\Facades\Log;

class KinoSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      return KinoSession::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      $movie = KinoSession::create($request->all());
      return response()->json($movie, 201);
    }

    public function create(Request $request)
    {
      // Валидация входящих данных
      $validated = $request->validate([
        'movie_id' => 'required|integer',
        'hall_id' => 'required|integer',
        'start_datetime' => 'required|date_format:Y-m-d H:i:s',
      ]);

      // Создание записи с массовым назначением
      $kinoSession = KinoSession::create([
        'movie_id' => $validated['movie_id'],
        'hall_id' => $validated['hall_id'],
        'start_datetime' => $validated['start_datetime'],
      ]);

      // Связываем места с конкретным сеансом . обработка с отправлением по одному запросу к БД
      // $seats = Seat::where('hall_id', $validated['hall_id'])->get();
      // foreach ($seats as $seat) {
      //     Log::info('Создание места:', [
      //       'seat' => $seat,
      //     ]);
      //     $seat->update(['session_id' => $kinoSession->id]);
      // }

      $hall = Hall::find($validated['hall_id']);
      $hall_config = [
        'rows' => $hall->rows, // количество рядов
        'seats_per_row' => $hall->seats_per_row // количество мест в ряду
      ];

      // Создание новых мест
      $seatsData = [];
      for ($row = 1; $row <= $hall_config['rows']; $row++) {
        for ($number = 1; $number <= $hall_config['seats_per_row']; $number++) {
          $seatsData[] = [
            'hall_id' => $validated['hall_id'],
            'session_id' => $kinoSession->id,
            'row' => $row,
            'number' => $number,
            'type' => 'standart',
            'taken' => 0,
            'status' => 'available'
          ];          
        }
      }
      Seat::insert($seatsData); // массосое добавление сидений

      return response()->json(['success' => true, 'kinoSession' => $kinoSession, 'message' => 'Сессия успешно создана'], status: 201);
      // return response()->json([
      //   'success' => true,
      //     'data' => [
      //       'id' => $kinoSession->id,
      //       'movie_id' => $kinoSession->movie_id,
      //       'hall_id' => $kinoSession->hall_id,
      //       'start_datetime' => $kinoSession->start_datetime
      //     ]
      // ], 201);
      
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
      return KinoSession::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
      $movie = KinoSession::findOrFail($id);
      $movie->update($request->all());
      return response()->json($movie);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
      $kinoSession = KinoSession::find($id); 
      if(!$kinoSession) {
        return response()->json(['message' => 'Session movie not found'], 404);
      }
      $kinoSession->delete();

      // 204 No Content без тела
      return response()->json(null, 204);

      // KinoSession::destroy($id);
      // return response()->json(null, 204);
    }
}
