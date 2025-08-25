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
use App\Http\Controllers\SessionController;

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
Route::get('/hall/add', [HallController::class, 'index']);

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
Route::post('/hall/create', [HallController::class, 'create']); // создание зала
Route::get('/hall/create', [HallController::class, 'index']); // проверка зала
Route::get('/hall/index', [HallController::class, 'index']);
Route::delete('/hall/destroy/{id}', [HallController::class, 'destroy']); // удаление зала
Route::post('/movies/create', [MovieController::class, 'create']); // создание кино
Route::delete('/movies/destroy/{id}', [MovieController::class, 'destroy']); // удаление кино
Route::post('/movies/update/{id}', [MovieController::class, 'update']); // редактирование кино
Route::post('/movies/session/create', [KinoSessionController::class, 'create']); // созданиие сессии кино
Route::delete('/movies/session/destroy/{id}', [KinoSessionController::class, 'destroy']); // удаление сессии кино

// Route::post('/sessions/{session}/open', [SessionController::class, 'openSales'])->name('sessions.open'); // старое
// Route::post('/sessions/{session}/close', [SessionController::class, 'closeSales'])->name('sessions.close'); // старое
Route::post('/admin/sales/open-all', [SessionController::class, 'openAllSales'])->name('admin.sales.openAll');
Route::post('/admin/sales/close-all', [SessionController::class, 'closeAllSales'])->name('admin.sales.closeAll');
Route::get('/admin/sales/status', [SessionController::class, 'status'])->name('admin.sales.status');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

