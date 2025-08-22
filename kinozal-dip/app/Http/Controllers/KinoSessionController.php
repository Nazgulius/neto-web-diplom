<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KinoSession;

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
        'start_time' => 'required|string',
      ]);

      // Создание записи с массовым назначением
      $kinoSession = KinoSession::create([
        'movie_id' => $validated['movie_id'],
        'hall_id' => $validated['hall_id'],
        'start_time' => $validated['start_time'],
      ]);

      return response()->json(['success' => true, 'kinoSession' => $kinoSession], 201);
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
