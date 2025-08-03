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
      Movie::destroy($id);
      return response()->json(null, 204);
    }
}
