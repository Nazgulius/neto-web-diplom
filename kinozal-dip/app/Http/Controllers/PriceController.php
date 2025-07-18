<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Price;

class PriceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      return Price::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      $movie = Price::create($request->all());
      return response()->json($movie, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
      return Price::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
      $movie = Price::findOrFail($id);
      $movie->update($request->all());
      return response()->json($movie);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
      Price::destroy($id);
      return response()->json(null, 204);
    }
}
