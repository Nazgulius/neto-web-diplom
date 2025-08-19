<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      return Movie::all();
      //  return response()->json(Movie::all()); // альтернативный вариант
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      $movie = Movie::create($request->all());
      return response()->json($movie, 201);
    }

    public function create(Request $request)
  {
    // Валидация входящих данных
    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'description' => 'required|string|max:2550',
      'duration' => 'required|integer',
      'country' => 'required|string',
      'image_url' => 'required|string',
    ]);

    // Создание записи с массовым назначением
    $movie = Movie::create([
      'title' => $validated['title'],
      'description' => $validated['description'],
      'duration' => $validated['duration'],
      'country' => $validated['country'],
      'image_url' => $validated['image_url'],
    ]);

    return response()->json(['success' => true, 'movie' => $movie], 201);
  }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
      return Movie::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
      $movie = Movie::findOrFail($id);
      $movie->update($request->all());
      return response()->json($movie);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
      $movie = Movie::find($id); 
      if(!$movie) {
        return response()->json(['message' => 'Movie not found'], 404);
      }
      $movie->delete();

      // 204 No Content без тела
      return response()->json(null, 204);

      // Movie::destroy($id);
      // return response()->json(null, 204);
    }
}
