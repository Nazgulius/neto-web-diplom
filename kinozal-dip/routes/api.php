<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HallController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\KinoSessionController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

Route::apiResources([
    'halls' => HallController::class,
    'rooms' => RoomController::class,
    'seats' => SeatController::class,
    'movies' => MovieController::class,
    'sessions' => KinoSessionController::class,
    'tickets' => TicketController::class,
    'prices' => PriceController::class,
    'users' => UserController::class,
]);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/check-seat', [SeatController::class, 'checkAvailability']);
Route::post('/reserve-seats', [SeatController::class, 'reserveSeats']);
Route::get('/seats', [SeatController::class, 'getSeats']);

// Route::get('/movies', [MovieController::class, 'index']);

// Защищённые маршруты
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    // защищённые API

    // API для админки и бронирования
    Route::apiResource('movies', MovieController::class);
    Route::apiResource('sessions', KinoSessionController::class);
    Route::apiResource('halls', HallController::class);
    Route::apiResource('rooms', RoomController::class);
    Route::apiResource('seats', SeatController::class);
    Route::apiResource('tickets', TicketController::class);
    Route::apiResource('prices', PriceController::class);
    Route::apiResource('users', UserController::class);
});

Route::get('/halls/{hall}/seances/{seance}/seats', [HallController::class, 'getSeatsStatus']);