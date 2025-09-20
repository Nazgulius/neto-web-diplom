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
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// пути для Inertia
Route::get('/', function () {
    return Inertia::render('App');
})->name('app'); // обновлён переход. Начало в App vue, и в нём уже будут открываться другие страницы приложения.

// // маршруты для клиента:
Route::get('/index', function () { // поменял с / на / index 
    return Inertia::render('client/Index');
})->name('home');

Route::get('/hall', function () {
    return Inertia::render('client/Hall');
})->name('hall.main');
Route::get('/hall/{hallId}/session/{sessionId}', function ($hallId, $sessionId) {
    return Inertia::render('client/Hall', props: [
        'hallId' => $hallId,
        'sessionId' => $sessionId,
    ]);
})->name('hallsession');

Route::get('/payment', function () {
    return Inertia::render('client/Payment');
})->name('payment');

Route::get('/ticket', function () {
    return Inertia::render('client/Ticket');
})->name('ticket');

// маршруты для администрации:
// Route::get('/dashboard', function () {
//     return Inertia::render('admin/Index');
// })->middleware(['auth', 'verified'])->name('dashboard');
// Route::get('/admin', function () {
//     return Inertia::render('auth/Login', [
//       'canResetPassword' => true, // или false
//   ]);
// })->middleware(['auth', 'verified'])->name('admin.login');
Route::get('/admin/index', function () {
    return Inertia::render('admin/Index');
})->middleware(['auth', 'verified'])->name('admin.login2');
// Route::get('/admin/login', function () {
//     return Inertia::render('admin/Login');
// })->middleware(['auth', 'verified'])->name('loginAdmin');

// пути для сервера
Route::get('/hall/add', [HallController::class, 'index']);
Route::get('/hall/create', [HallController::class, 'index']); // проверка зала
Route::get('/hall/index', [HallController::class, 'index']);
Route::get('/hall/hall/{id}', [HallController::class, 'show']);
Route::post('/halls/update-prices', [HallController::class, 'updatePrices']);
Route::post('/halls/hall/update-seats', [HallController::class, 'updateSeats']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/api/logout', [AuthController::class, 'logout'])
  ->middleware('auth:sanctum');
// Route::post('/logout', function (Request $request) {
//   $request->user()->tokens()->delete();
//   return response('Successfully logged out.', 200);
// });
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
  ->middleware('auth')
  ->name('logout');

Route::get('/movies', [MovieController::class, 'index']);
Route::get('/hall/movie/{id}', [MovieController::class, 'show']);
// Route::get('/movies/{date?}', [MovieController::class, 'index'])->name('movies');

Route::get('/seats/index', [SeatController::class, 'index']);
Route::post('/block-seats', [SeatController::class, 'blockSeats']); // блокирует места
Route::post('/reserve-seats', [SeatController::class, 'reserveSeats']); // бронирует места
Route::post('/censel-block-seats', [SeatController::class, 'censelBlockSeats']); // отмена блокирования места

Route::get('/sessions', [KinoSessionController::class, 'index']);
Route::get('/hall/sessions/{id}', [KinoSessionController::class, 'show']);
Route::post('/api/book', [TicketController::class, 'book'])->name('tickets.book');
Route::get('/ticket/{uuid}', [TicketController::class, 'show'])->name('ticket.show');
Route::get('/get-qr-code', [TicketController::class, 'getQrCode']);
Route::post('/get-qr-code', [TicketController::class, 'getQrCode']);

Route::get('/halls/{hallId}/config', [HallController::class, 'getHallConfig'])->name('hall.config');
Route::post('/hall/create', [HallController::class, 'create']); // создание зала
Route::post('/movies/create', [MovieController::class, 'create']); // создание кино
Route::post('/movies/update/{id}', [MovieController::class, 'update']); // редактирование кино
Route::post('/movies/session/create', [KinoSessionController::class, 'create']); // созданиие сессии кино

Route::delete('/hall/destroy/{id}', [HallController::class, 'destroy']); // удаление зала
Route::delete('/movies/destroy/{id}', [MovieController::class, 'destroy']); // удаление кино
Route::delete('/movie/destroy/{id}', [MovieController::class, 'destroy']); // удаление кино
Route::delete('/movies/session/destroy/{id}', [KinoSessionController::class, 'destroy']); // удаление сессии кино

Route::get('/admin/sales/status', [SessionController::class, 'status'])->name('admin.sales.status');
Route::post('/admin/sales/open-all', [SessionController::class, 'openAllSales'])->name('admin.sales.openAll');
Route::post('/admin/sales/close-all', [SessionController::class, 'closeAllSales'])->name('admin.sales.closeAll');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

