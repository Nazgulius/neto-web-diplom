<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\MovieController;
use App\Http\Controllers\HallController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\KinoSessionController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

// Route::apiResources([
//     'halls' => HallController::class,
//     'rooms' => RoomController::class,
//     'seats' => SeatController::class,
//     'movies' => MovieController::class,
//     'sessions' => KinoSessionController::class,
//     'tickets' => TicketController::class,
//     'prices' => PriceController::class,
//     'users' => UserController::class,
// ]);

// эти импорты скорее всего не нужны. по окончанию настройки роутов удалить.
// import { createRouter, createWebHistory } from 'vue-router' ;
// import Index from '@/views/Index.vue' ;
// import Hall from '@/views/Hall.vue' ;
// import Payment from '@/views/Payment.vue' ;
// import Ticket from '@/views/Ticket.vue' ;
// import AdminIndex from '@/views/admin/AdminIndex.vue' ;
// import AdminLogin from '@/views/admin/AdminLogin.vue' ;

// Route::get('/', function () {
//     return Inertia::render('Welcome');
// })->name('home');

// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// маршруты для клиента:
Route::get('/hall', function () {
    return Inertia::render('client/Hall');
})->name('hall');
// Route::get('/hall/{hallId}/session/{sessionId}', function () {
//     return Inertia::render('client/Hall');
// })->name('hall');

Route::get('/', function () {
    return Inertia::render('client/Index');
})->name('home');

Route::get('/payment', function () {
    return Inertia::render('client/Payment');
})->name('payment');

Route::get('/ticket', function () {
    return Inertia::render('client/Ticket');
})->name('ticket');

// маршруты для администрации:
Route::get('dashboard', function () {
    return Inertia::render('admin/Index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('dashboard/login', function () {
    return Inertia::render('admin/Login');
})->middleware(['auth', 'verified'])->name('loginAdmin');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/movies', [MovieController::class, 'index']);
Route::get('/seats', [SeatController::class, 'index']);
Route::post('/seats/reserve', [SeatController::class, 'reserve']);
Route::post('/check-seat', [SeatController::class, 'checkAvailability']);
Route::post('/reserve-seats', [SeatController::class, 'reserveSeats']);
Route::get('/seats', [SeatController::class, 'getSeats']);
Route::post('/api/book', [TicketController::class, 'book'])->name('tickets.book');
Route::get('/ticket/{uuid}', [TicketController::class, 'show'])->name('ticket.show');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

