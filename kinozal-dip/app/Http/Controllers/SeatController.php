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

   

    

    public function reserveSeats(Request $request)
    {
        $seats = $request->input('seats'); // массив сидений

        try {
          // Обновление статуса для выбранных сидений
          Seat::whereIn('id', $seats)->update(['status' => 'booked']);

          return response()->json([
            'success' => true,
            'message' => 'Бронирование успешно'
          ], 201);
        } catch (\Exception $e) {
          return response()->json([
            'success' => false,
            'message' => 'Ошибка бронирования'
          ], 400);
        }
      
        // foreach ($seats as $seatId) {
        //     $seat = Seat::find($seatId);
        //     if ($seat && $seat->status === 'blocked' || $seat->status === 'available') {  
        //         $seat->status = 'booked';
        //         $seat->save();
        //     }
        // }

        // return response()->json(['success' => true]);
    }
    public function blockSeats(Request $request)
    {      
      $seatIds = $request->input('seats'); // массив id
      
      try {
          // Обновление статуса для выбранных сидений
          Seat::whereIn('id', $seatIds)->update(['status' => 'blocked']);

          return response()->json(['message' => 'Seats reserved successfully']);
      } catch (\Exception $e) {
          return response()->json(['error' => 'Failed to reserve seats'], 500);
      }

        // $seats = $request->input('seats'); // массив сидений
        // echo 'blockSeats'.$seats."\n";
        // echo 'blockSeats'.$seats[0]."\n";
        // echo 'blockSeats'.$seats[1]."\n";
       

        // foreach ($seats as $seatId) {
        //   echo 'blockSeats'.$seatId."\n";
        //     $seat = Seat::find($seatId);
        //     if ($seat && $seat->status === 'available') {
        //         $seat->status = 'blocked ';
        //         $seat->save();
        //     }
        // }

        // return response()->json(['success' => true]);
    }

    // отмена блокирования, присвоение available
    public function censelBlockSeats(Request $request)
    {      
      $seatIds = $request->input('seats'); // массив id
      
      try {
          // Обновление статуса для выбранных сидений
          Seat::whereIn('id', $seatIds)->update(['status' => 'available']);

          return response()->json(['message' => 'Reservation cancelled']);
      } catch (\Exception $e) {
          return response()->json(['error' => 'Failed to reservation cancelled seats'], 500);
      }
        
    }

    
}
