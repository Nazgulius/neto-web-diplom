<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\MovieController;
use App\Http\Controllers\HallController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\KinoSessionController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

// Route::get('/home', function () {
//     return Inertia::render('Welcome');
// });
// ->name('home');

// Route::get('dashboard2', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified']);
//->name('dashboard');

Route::get('/', function () {
    return Inertia::render('App');
})->name('app'); // обновлён переход. Начало в App vue, и в нём уже будут открываться другие страницы приложения.

// маршруты для клиента:
Route::get('/index', function () { // поменял с / на / index 
    return Inertia::render('client/Index');
})->name('home');

Route::get('/hall', function () {
    return Inertia::render('client/Hall');
})->name('hall');
Route::get('/hall/{hallId}/session/{sessionId}', function ($hallId, $sessionId) {
    return Inertia::render('client/Hall',[
        'hallId' => $hallId,
        'sessionId' => $sessionId,
    ]);
})->name('hall.id');

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
Route::get('/admin', function () {
    return Inertia::render('admin/Index');
})->middleware(['auth', 'verified']);

Route::get('dashboard/login', function () {
    return Inertia::render('admin/Login');
})->middleware(['auth', 'verified'])->name('loginAdmin');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/movies', [MovieController::class, 'index']);
Route::get('/seats', [SeatController::class, 'index']);
Route::get('/sessions', [KinoSessionController::class, 'index']);
Route::post('/seats/reserve', [SeatController::class, 'reserve']);
Route::post('/check-seat', [SeatController::class, 'checkAvailability']);
Route::post('/reserve-seats', [SeatController::class, 'reserveSeats']);
Route::get('/seats', [SeatController::class, 'getSeats']);
Route::post('/api/book', [TicketController::class, 'book'])->name('tickets.book');
Route::get('/ticket/{uuid}', [TicketController::class, 'show'])->name('ticket.show');
Route::get('/get-qr-code', [TicketController::class, 'getQrCode']);
Route::post('/get-qr-code', [TicketController::class, 'getQrCode']);
Route::post('/api/logout', [AuthController::class, 'logout']);
Route::post('/hall/create', [HallController::class, 'logout']);

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

