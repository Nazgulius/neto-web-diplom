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