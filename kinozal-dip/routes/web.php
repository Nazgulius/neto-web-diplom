<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

import { createRouter, createWebHistory } from 'vue-router' ;
import Index from '@/views/Index.vue' ;
import Hall from '@/views/Hall.vue' ;
import Payment from '@/views/Payment.vue' ;
import Ticket from '@/views/Ticket.vue' ;
import AdminIndex from '@/views/admin/AdminIndex.vue' ;
import AdminLogin from '@/views/admin/AdminLogin.vue' ;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// добавил
// Route::get('/{any}', function () {
//   return inertia('Welcome'); 
// })->where('any', '.*');

// маршруты для клиента:
Route::get('/hall', function () {
    return Inertia::render('Hall');
});

Route::get('/index', function () {
    return Inertia::render('Index');
});

Route::get('/payment', function () {
    return Inertia::render('Payment');
});

Route::get('/ticket', function () {
    return Inertia::render('Ticket');
});

// маршруты для администрации:
Route::get('dashboard/index', function () {
    return Inertia::render('Index');
})->middleware(['auth', 'verified'])->name('index');

Route::get('dashboard/login', function () {
    return Inertia::render('Login');
})->middleware(['auth', 'verified'])->name('login');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

