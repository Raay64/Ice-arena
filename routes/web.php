<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MainController::class, 'index'])->name('home');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/payment/success/{booking}', [BookingController::class, 'success'])->name('payment.success');
Route::get('/payment/cancel/{booking}', [BookingController::class, 'cancel'])->name('payment.cancel');
Route::get('/payment/check/{booking}', [BookingController::class, 'checkStatus'])->name('payment.check');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::get('/register', [AdminAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AdminAuthController::class, 'register']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

Route::prefix('admin')->name('admin.')->middleware('admin.auth')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/skates', [AdminController::class, 'skatesIndex'])->name('skates');
    Route::get('/skates/create', [AdminController::class, 'skatesCreate'])->name('skates.create');
    Route::post('/skates', [AdminController::class, 'skatesStore'])->name('skates.store');
    Route::get('/skates/{skate}/edit', [AdminController::class, 'skatesEdit'])->name('skates.edit');
    Route::put('/skates/{skate}', [AdminController::class, 'skatesUpdate'])->name('skates.update');
    Route::delete('/skates/{skate}', [AdminController::class, 'skatesDestroy'])->name('skates.destroy');
    Route::get('/bookings', [AdminController::class, 'bookingsIndex'])->name('bookings');
    Route::get('/bookings/{booking}', [AdminController::class, 'bookingsShow'])->name('bookings.show');
    Route::get('/tickets', [AdminController::class, 'ticketsIndex'])->name('tickets');
});
