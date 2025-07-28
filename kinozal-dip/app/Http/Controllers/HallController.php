<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hall;
use App\Models\KinoSession;
use App\Models\Seat;
use App\Models\Ticket;

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
      $movie = Hall::findOrFail($id);
      $movie->update($request->all());
      return response()->json($movie);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
      Hall::destroy($id);
      return response()->json(null, 204);
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
