<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
      // return Movie::all();
      //  return response()->json(Movie::all()); // альтернативный вариант


      $date = $request->input('date');
    
      // Здесь можно добавить фильтрацию по дате
      $movies = Movie::all();
      
      if ($date) {
          // Пример фильтрации (предполагается, что в модели Movie есть поле date)
          $movies = Movie::whereDate('date', $date)->get();
      }
      
      return response()->json($movies);
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
      'description' => 'required|string',
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

    public function updateMovie(Request $request)
  {
    $movie = Movie::find($request->hall_id);

    if (!$movie) {
      return response()->json(['error' => 'Фильм не найден'], 404);
    }

    $movie->update([
      'title' => $request->title['title'],
      'description' => $request->description['description'],
      'duration' => $request->duration['duration'],
      'country' => $request->country['country'],
      'image_url' => $request->image_url['image_url'],
    ]);

    return response()->json(['message' => 'Фильм успешно обновлён'], 200);
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
