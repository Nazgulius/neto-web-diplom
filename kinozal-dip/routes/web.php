<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\MovieController;

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


Route::get('/movies', [MovieController::class, 'index']);

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

