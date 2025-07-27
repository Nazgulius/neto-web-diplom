<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seat;

class SeatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      return Seat::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      $movie = Seat::create($request->all());
      return response()->json($movie, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
      return Seat::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
      $movie = Seat::findOrFail($id);
      $movie->update($request->all());
      return response()->json($movie);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
      Seat::destroy($id);
      return response()->json(null, 204);
    }

    public function reserve(Request $request)
    {
        $seatIds = $request->input('seats'); // массив id

        // Обновим статус выбранных сидений
        // Предположим, есть поле 'taken' или 'status'
        // Например: 0 - свободно, 1 - забронировано
        // Или используется логика с массивом занятых, в этом случае:
        // обновим их статус
        try {
            // Обновление статуса для выбранных сидений
            Seat::whereIn('id', $seatIds)->update(['taken' => true]);

            return response()->json(['message' => 'Seats reserved successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to reserve seats'], 500);
        }
    }
}
